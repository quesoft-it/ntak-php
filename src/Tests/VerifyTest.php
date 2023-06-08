<?php

namespace Natsu007\Ntak\Tests;

use Carbon\Carbon;
use Natsu007\Ntak\NTAK;
use Natsu007\Ntak\NTAKClient;
use Natsu007\Ntak\Responses\NTAKVerifyResponse;
use Natsu007\Ntak\TestCase;

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
                $this->keyPath,
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
