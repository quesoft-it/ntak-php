<?php

namespace QueSoft\Ntak\Models;

use QueSoft\Ntak\Enums\NTAKPaymentType;

class NTAKPayment
{
    protected $round = 0;
    public $paymentType;
    public $total;

    /**
     * __construct
     *
     * @param  NTAKPaymentType $paymentType
     * @return void
     */
    public function __construct(
        NTAKPaymentType $paymentType,
        int             $total
    ) {
        $this->paymentType = $paymentType;
        $this->total       = $total;
    }

    /**
     * buildRequest
     *
     * @return array
     */
    public function buildRequest(): array
    {
        $rounded = 0;
        $request = [
            'fizetesiMod'       => $this->paymentType->getKey(),
            'fizetettOsszegHUF' => $this->paymentType != NTAKPaymentType::KESZPENZHUF()
                ? $this->total
                : $rounded = (int) round($this->total / 5) * 5
        ];

        if ($this->paymentType == NTAKPaymentType::KESZPENZHUF()) {
            $this->round = $this->total - $rounded;
        }

        return $request;
    }

    /**
     * round getter
     *
     * @return int
     */
    public function round(): int
    {
        return $this->round;
    }
}
