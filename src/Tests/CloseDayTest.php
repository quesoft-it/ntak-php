<?php

namespace QueSoft\Ntak\Tests;

use Carbon\Carbon;
use QueSoft\Ntak\Enums\NTAKDayType;
use QueSoft\Ntak\NTAK;
use QueSoft\Ntak\NTAKClient;
use QueSoft\Ntak\TestCase;

class CloseDayTest extends TestCase
{
    /**
     * test_close_day
     *
     * @return void
     */
    public function test_close_day(): void
    {
        $response = NTAK::message(
            $client = new NTAKClient(
                $this->taxNumber,
                $this->regNumber,
                $this->softwareRegNumber,
                $this->version,
                $this->certPath,
                true
            ),
            Carbon::now()
        )->closeDay(
            Carbon::now()->addHours(-8),
            Carbon::now()->addMinutes(-2),
            NTAKDayType::NORMAL_NAP()
        );

        $this->assertIsString($response);
        $this->assertIsArray($client->lastRequest());
        $this->assertIsArray($client->lastResponse());
    }
}
