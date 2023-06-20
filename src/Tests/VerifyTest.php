<?php

namespace QueSoft\Ntak\Tests;

use Carbon\Carbon;
use QueSoft\Ntak\NTAK;
use QueSoft\Ntak\NTAKClient;
use QueSoft\Ntak\Responses\NTAKVerifyResponse;
use QueSoft\Ntak\TestCase;

class VerifyTest extends TestCase
{
    /**
     * test_verify
     *
     * @return void
     */
    public function test_verify(): void
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
        )->verify('cfb3197a-a70d-4ba0-8de1-c1e6306c9fe8');

        $this->assertInstanceOf(NTAKVerifyResponse::class, $response);
        $this->assertTrue($response->successful());
        $this->assertIsArray($client->lastRequest());
        $this->assertIsArray($client->lastResponse());
        $this->assertIsInt($client->lastRequestTime());
    }
}
