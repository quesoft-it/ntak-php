<?php

namespace QueSoft\Ntak\Tests;

use Carbon\Carbon;
use QueSoft\Ntak\Enums\NTAKAmount;
use QueSoft\Ntak\Enums\NTAKCategory;
use QueSoft\Ntak\Enums\NTAKOrderType;
use QueSoft\Ntak\Enums\NTAKPaymentType;
use QueSoft\Ntak\Enums\NTAKSubcategory;
use QueSoft\Ntak\Enums\NTAKVat;
use QueSoft\Ntak\Models\NTAKOrder;
use QueSoft\Ntak\Models\NTAKOrderItem;
use QueSoft\Ntak\Models\NTAKPayment;
use QueSoft\Ntak\NTAK;
use QueSoft\Ntak\NTAKClient;
use QueSoft\Ntak\TestCase;
use Ramsey\Uuid\Uuid;

class StoreOrderTest extends TestCase
{
    protected $client;

    /**
     * test_store_order
     *
     * @return void
     */
    public function test_store_order(): void
    {
        $when = Carbon::now()->addMinutes(-1);

        $response = $this->ntak()->handleOrder(
            $this->ntakOrder($when, NTAKOrderType::NORMAL())
        );

        $this->assertIsString($response);
        $this->assertIsArray($this->client->lastRequest());
        $this->assertIsArray($this->client->lastResponse());
    }

    // public function test_destroy_order(): void
    // {
    //     // Create order
    //     $when = Carbon::now()->addMinutes(-1);

    //     $response = $this->ntak()->handleOrder(
    //         $ntakOrder = $this->ntakOrder($when, NTAKOrderType::NORMAL())
    //     );

    //     // Destroy order
    //     $when = Carbon::now()->addMinutes(-1);

    //     $response = $this->ntak()->handleOrder(
    //         $ntakOrder = $this->ntakOrder($when, NTAKOrderType::NORMAL())
    //     );
    // }

    /**
     * ntak
     *
     * @return NTAK
     */
    protected function ntak(): NTAK
    {
        return NTAK::message(
            $this->client = new NTAKClient(
                $this->taxNumber,
                $this->regNumber,
                $this->softwareRegNumber,
                $this->version,
                $this->certPath,
                true
            ),
            Carbon::now()
        );
    }

    /**
     * orderItems
     *
     * @param  Carbon $when
     * @return array
     */
    protected function orderItems(Carbon $when): array
    {
        return [
            new NTAKOrderItem(
                'Absolut vodka',
                NTAKCategory::ALKOHOLOSITAL(),
                NTAKSubcategory::PARLAT(),
                NTAKVat::C_27(),
                1000,
                NTAKAmount::LITER(),
                0.04,
                2,
                $when
            ),
            new NTAKOrderItem(
                'Túró rudi',
                NTAKCategory::ETEL(),
                NTAKSubcategory::SNACK(),
                NTAKVat::A_5(),
                500,
                NTAKAmount::DARAB(),
                1,
                2,
                $when
            )
        ];
    }

    /**
     * ntakOrder
     *
     * @param  Carbon        $when
     * @param  NTAKOrderType $orderType
     * @return NTAKOrder
     */
    protected function ntakOrder(Carbon $when, NTAKOrderType $orderType): NTAKOrder
    {
        return new NTAKOrder(
            $orderType,
            Uuid::uuid4(),
            $this->orderItems($when),
            null,
            $when->copy()->addMinutes(-7),
            $when,
            true,
            [
                new NTAKPayment(
                    NTAKPaymentType::KESZPENZHUF(),
                    3000 * 0.8 + 3000 * 0.8 * 0.1
                )
            ],
            20,
            10
        );
    }
}
