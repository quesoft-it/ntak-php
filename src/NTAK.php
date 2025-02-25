<?php

namespace QueSoft\Ntak;

use Carbon\Carbon;
use QueSoft\Ntak\Enums\NTAKCategory;
use QueSoft\Ntak\Enums\NTAKDayType;
use QueSoft\Ntak\Enums\NTAKOrderType;
use QueSoft\Ntak\Enums\NTAKSubcategory;
use QueSoft\Ntak\Enums\NTAKVerifyStatus;
use QueSoft\Ntak\Models\NTAKOrder;
use QueSoft\Ntak\Responses\NTAKVerifyResponse;

class NTAK
{
    protected      $client;
    protected      $when;

    /**
     * __construct
     *
     * @param  NTAKClient $client
     * @param  Carbon     $when
     * @return void
     */
    public function __construct(
        NTAKClient $client,
        Carbon $when
    ) {
        $this->client = $client;
        $this->when   = $when;
    }

    /**
     * Lists the categories
     *
     * @return array|NTAKCategory[]
     */
    public static function categories(): array
    {
        return NTAKCategory::cases();
    }

    /**
     * Lists the subcategories of a category
     *
     * @param  NTAKCategory $category
     * @return array|NTAKSubcategory[]
     */
    public static function subcategories(NTAKCategory $category): array
    {
        return $category->subcategories();
    }

    /**
     * message
     *
     * @param  NTAKClient $client
     * @param  Carbon     $when
     * @return NTAK
     */
    public static function message(NTAKClient $client, Carbon $when): NTAK
    {
        return new static($client, $when);
    }

    /**
     * fake
     *
     * @param  array $expectedResponse
     * @return NTAK
     */
    public function fake(array $expectedResponse): NTAK
    {
        $this->client->fakeResponse($expectedResponse);
        return $this;
    }

    /**
     * handleOrder
     *
     * @param  NTAKOrder $ntakOrders
     * @return string
     */
    public function handleOrder(NTAKOrder ...$ntakOrders): string
    {
        $orders = [];
        foreach ($ntakOrders as $ntakOrder) {
            $orders[] = [
                'rendelesBesorolasa'           => $ntakOrder->orderType->getKey(),
                'rmsRendelesAzonosito'         => $ntakOrder->orderId,
                'hivatkozottRendelesOsszesito' => $ntakOrder->orderType == NTAKOrderType::NORMAL()
                    ? null
                    : $ntakOrder->ntakOrderId,
                'targynap'                     => $ntakOrder->end->format('Y-m-d'),
                'rendelesKezdete'              => $ntakOrder->orderType == NTAKOrderType::SZTORNO()
                    ? null
                    : $ntakOrder->start->timezone('Europe/Budapest')->toIso8601String(),
                'rendelesVege'                 => $ntakOrder->orderType == NTAKOrderType::SZTORNO()
                    ? null
                    : $ntakOrder->end->timezone('Europe/Budapest')->toIso8601String(),
                'helybenFogyasztott'           => $ntakOrder->isAtTheSpot,
                'osszesitett'                  => $ntakOrder->orderType == NTAKOrderType::NORMAL()
                    ? $ntakOrder->aggregated
                    : false,
                'osszesitettIndoklasa'         => ($ntakOrder->orderType == NTAKOrderType::NORMAL() && $ntakOrder->aggregated === true)
                    ? $ntakOrder->aggregatedCause->getKey()
                    : null,
                'fizetesiInformaciok'          => $ntakOrder->orderType == NTAKOrderType::SZTORNO()
                    ? null
                    : [
                        'rendelesVegosszegeHUF' => $ntakOrder->totalWithDiscount(),
                        'fizetesiModok'         => $ntakOrder->buildPaymentTypes(),
                    ],
                'rendelesTetelek'              => $ntakOrder->orderType == NTAKOrderType::SZTORNO()
                    ? null
                    : $ntakOrder->buildOrderItems(),
            ];
        }

        $message = [
            'rendelesOsszesitok' => $orders,
        ];

        return $this->client->message(
            $message,
            $this->when,
            '/rms/rendeles-osszesito'
        )['feldolgozasAzonosito'];
    }

    /**
     * resendMessage - Resend previously sent message.
     * Requires decoded array contents from 
     * previously sent request's lastRequest message.
     * Useful when you need to resend order or close by verify request.
     * IMPORTANT to set message object when parameter to exactly the same timestamp of previous message!
     *
     * @param  array[] $message
     * @param bool $order
     * @return string
     */
    public function resendMessage($message = [], $order = true): string
    {    
        $uri = ($order) ? '/rms/rendeles-osszesito' : '/rms/napi-zaras';

        return $this->client->message(
            $message,
            $this->when,
            $uri
        )['feldolgozasAzonosito'];
    }

    /**
     * closeDay
     *
     * @param  Carbon      $start
     * @param  Carbon      $end
     * @param  NTAKDayType $dayType
     * @param  int         $tips
     * @return string
     */
    public function closeDay(
        Carbon      $start,
        ?Carbon     $end = null,
        NTAKDayType $dayType,
        int         $tips = 0
    ): string {
        $message = [
            'zarasiInformaciok' => [
                'targynap'           => $start->format('Y-m-d'),
                'targynapBesorolasa' => $dayType->getKey(),
                'nyitasIdopontja'    => $dayType != NTAKDayType::ADOTT_NAPON_ZARVA()
                    ? $start->timezone('Europe/Budapest')->toIso8601String()
                    : null,
                'zarasIdopontja'     => $dayType != NTAKDayType::ADOTT_NAPON_ZARVA()
                    ? $end->timezone('Europe/Budapest')->toIso8601String()
                    : null,
                'osszesBorravalo'    => $tips,
            ],
        ];

        return $this->client->message(
            $message,
            $this->when,
            '/rms/napi-zaras'
        )['feldolgozasAzonosito'];
    }

    /**
     * verify
     *
     * @param  string $processId
     * @return NTAKVerifyResponse
     */
    public function verify(
        string $processId
    ): NTAKVerifyResponse {
        $message = [
            'feldolgozasAzonositok' => [
                [
                    'feldolgozasAzonosito' => $processId,
                ]
            ],
        ];

        $response = $this->client->message(
            $message,
            $this->when,
            '/rms/ellenorzes'
        )['uzenetValaszok'][0];

        return new NTAKVerifyResponse(
            $response['sikeresUzenetek'] ?? [],
            $response['sikertelenUzenetek'] ?? [],
            $response['fejlecHibak'] ?? [],
            new NTAKVerifyStatus($response['statusz'])
        );
    }
}
