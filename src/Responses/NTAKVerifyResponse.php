<?php

namespace QueSoft\Ntak\Responses;

use QueSoft\Ntak\Enums\NTAKVerifyStatus;

class NTAKVerifyResponse
{
    public       $successfulMessages;
    public       $unsuccessfulMessages;
    public       $headerErrors;
    public       $status;

    /**
     * __construct
     *
     * @param  array            $successfulMessages
     * @param  array            $unsuccessfulMessages
     * @param  array            $headerErrors
     * @param  NTAKVerifyStatus $status
     * @return void
     */
    public function __construct(
        array            $successfulMessages,
        array            $unsuccessfulMessages,
        array            $headerErrors,
        NTAKVerifyStatus $status
    ) {
        $this->successfulMessages   = $successfulMessages;
        $this->unsuccessfulMessages = $unsuccessfulMessages;
        $this->headerErrors         = $headerErrors;
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
