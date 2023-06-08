<?php

namespace Natsu007\Ntak\Models;

use Carbon\Carbon;
use InvalidArgumentException;
use Natsu007\Ntak\Enums\NTAKAmount;
use Natsu007\Ntak\Enums\NTAKCategory;
use Natsu007\Ntak\Enums\NTAKOrderType;
use Natsu007\Ntak\Enums\NTAKPaymentType;
use Natsu007\Ntak\Enums\NTAKSubcategory;
use Natsu007\Ntak\Enums\NTAKVat;

class NTAKOrder
{
    protected $total;
    protected $totalWithDiscount;
    public    $orderType;
    public    $orderId;
    public    $orderItems;
    public    $ntakOrderId;
    public    $start;
    public    $end;
    public    $isAtTheSpot;
    public    $payments;
    public    $discount;

    /**
     * __construct
     *
     * @param  NTAKOrderType            $orderType
     * @param  string                   $orderId
     * @param  array|NTAKOrderItem[]    $orderItems
     * @param  string                   $ntakOrderId
     * @param  Carbon                   $start
     * @param  Carbon                   $end
     * @param  bool                     $isAtTheSpot
     * @param  array|null|NTAKPayment[] $payments
     * @param  int                      $discount
     * @return void
     */
    public function __construct(
        NTAKOrderType    $orderType,
        string           $orderId,
        ?array           $orderItems = null,
        ?string          $ntakOrderId = null,
        ?Carbon          $start = null,
        ?Carbon          $end = null,
        bool             $isAtTheSpot = true,
        ?array           $payments = null,
        int              $discount = 0
    ) {
        $this->orderType    = $orderType;
        $this->orderId      = $orderId;
        $this->orderItems   = $orderItems;
        $this->ntakOrderId  = $ntakOrderId;
        $this->start        = $start;
        $this->end          = $end;
        $this->isAtTheSpot  = $isAtTheSpot;
        $this->payments     = $payments;
        $this->discount     = $discount;

        if ($orderType != NTAKOrderType::NORMAL()) {
            $this->validateIfNotNormal();
        }
        if ($orderType != NTAKOrderType::STORNO()) {
            $this->validateIfNotStorno();
        }

        $this->total = $this->calculateTotal();
        $this->totalWithDiscount = $this->calculateTotalWithDiscount();
    }

    /**
     * buildOrderItems
     *
     * @return array
     */
    public function buildOrderItems(): ?array
    {
        $orderItems = $this->orderItems === null
            ? null
            : array_map(
                function(NTAKOrderItem $orderItem) {
                    return $orderItem->buildRequest();
                },
                $this->orderItems
            );

        if ($orderItems !== null && $this->total > $this->totalWithDiscount) {
            $orderItems[] = $this->buildDiscountRequest();
        }

        return $orderItems;
    }

    /**
     * buildPaymentTypes
     *
     * @return array
     */
    public function buildPaymentTypes(): array
    {
        $payments = array_map(
            function(NTAKPayment $payment) {
                return $payment->buildRequest();
            }
            ,
            $this->payments
        );

        foreach ($this->payments as $payment) {
            if ($payment->round() !== 0) {
                $payments[] = [
                    'fizetesiMod'       => NTAKPaymentType::KEREKITES()->getKey(),
                    'fizetettOsszegHUF' => $payment->round(),
                ];

                break;
            }
        }

        return $payments;
    }

    /**
     * total getter
     *
     * @return int|null
     */
    public function total(): ?int
    {
        return $this->total;
    }

    /**
     * totalWithDiscount getter
     *
     * @return int|null
     */
    public function totalWithDiscount(): ?int
    {
        return $this->totalWithDiscount;
    }

    /**
     * validateIfNotNormal
     *
     * @return void
     * @throws InvalidArgumentException
     */
    protected function validateIfNotNormal(): void
    {
        if ($this->ntakOrderId === null) {
            throw new InvalidArgumentException('ntakOrderId cannot be null in this case');
        }
    }

    /**
     * validateIfNotStorno
     *
     * @return void
     * @throws InvalidArgumentException
     */
    protected function validateIfNotStorno(): void
    {
        if ($this->orderItems === null || count($this->orderItems) === 0) {
            throw new InvalidArgumentException('orderItems cannot be null in this case');
        }

        foreach ($this->orderItems as $orderItem) {
            if (! $orderItem instanceof NTAKOrderItem) {
                throw new InvalidArgumentException('orderItems must be an array of NTAKOrderItem instances');
            }
        }

        if ($this->start === null) {
            throw new InvalidArgumentException('start cannot be null in this case');
        }

        if ($this->end === null) {
            throw new InvalidArgumentException('end cannot be null in this case');
        }

        if (count($this->payments) === 0 || $this->payments === null) {
            throw new InvalidArgumentException('paymentType cannot be null in this case');
        }
    }

    /**
     * calculateTotal
     *
     * @return int
     */
    protected function calculateTotal(): ?int
    {
        return $this->orderType != NTAKOrderType::STORNO()
            ? array_reduce($this->orderItems, function (int $carry, NTAKOrderItem $orderItem) {
                return $carry + $orderItem->price * $orderItem->quantity;
            }, 0)
            : null;
    }

    /**
     * calculateTotalWithDiscount
     *
     * @return int
     */
    protected function calculateTotalWithDiscount(): ?int
    {
        if ($this->discount === 0) {
            return $this->total;
        }

        return $this->orderType != NTAKOrderType::STORNO()
            ? array_reduce($this->orderItems, function (int $carry, NTAKOrderItem $orderItem) {
                $price = ($orderItem->price * $orderItem->quantity) * (1 - $this->discount / 100);

                return $carry + $price;
            }, 0)
            : null;
    }

    /**
     * buildDiscountRequest
     *
     * @return array
     */
    protected function buildDiscountRequest(): array
    {
        return (
            new NTAKOrderItem(
                'Kedvezmény',
                NTAKCategory::EGYEB(),
                NTAKSubcategory::KEDVEZMENY(),
                NTAKVat::E_0(),
                $this->totalWithDiscount - $this->total,
                NTAKAmount::DARAB(),
                1,
                1,
                $this->end
            )
        )->buildRequest();
    }
}
