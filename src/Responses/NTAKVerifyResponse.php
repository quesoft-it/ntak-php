<?php

namespace Natsu007\Ntak\Responses;

use Natsu007\Ntak\Enums\NTAKVerifyStatus;

class NTAKVerifyResponse
{
    protected       $successfulMessages;
    protected       $unsuccessfulMessages;
    protected       $status;

    /**
     * __construct
     *
     * @param  array            $successfulMessages
     * @param  array            $unsuccessfulMessages
     * @param  NTAKVerifyStatus $status
     * @return void
     */
    public function __construct(
        array            $successfulMessages,
        array            $unsuccessfulMessages,
        NTAKVerifyStatus $status
    ) {
        $this->successfulMessages   = $successfulMessages;
        $this->unsuccessfulMessages = $unsuccessfulMessages;
        $this->status               = $status;
    }

    /**
     * successful
     *
     * @return bool
     */
    public function successful(): bool
    {
        return $this->status == NTAKVerifyStatus::TELJESEN_SIKERES();
    }

    /**
     * unsuccessful
     *
     * @return bool
     */
    public function unsuccessful(): bool
    {
        return ! $this->successful();
    }
}
