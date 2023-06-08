<?php

namespace Natsu007\Ntak\Tests;

use Carbon\Carbon;
use Natsu007\Ntak\Enums\NTAKAmount;
use Natsu007\Ntak\Enums\NTAKCategory;
use Natsu007\Ntak\Enums\NTAKOrderType;
use Natsu007\Ntak\Enums\NTAKPaymentType;
use Natsu007\Ntak\Enums\NTAKSubcategory;
use Natsu007\Ntak\Enums\NTAKVat;
use Natsu007\Ntak\Models\NTAKOrder;
use Natsu007\Ntak\Models\NTAKOrderItem;
use Natsu007\Ntak\Models\NTAKPayment;
use Natsu007\Ntak\NTAK;
use Natsu007\Ntak\NTAKClient;
use Natsu007\Ntak\TestCase;
use Ramsey\Uuid\Uuid;

class StoreOrderTest extends TestCase
{
    /**
     * test_store_order
     *
     * @return void
     */
    public function test_store_order(): void
    {
        $when = Carbon::now()->addMinutes(-1);

        $orderItems = [
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
                NTAKVat::C_27(),
                1001,
                NTAKAmount::DARAB(),
                1,
                2,
                $when
            )
        ];

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
        )->handleOrder(
            new NTAKOrder(
                NTAKOrderType::NORMAL(),
                Uuid::uuid4(),
                $orderItems,
                null,
                $when->copy()->addMinutes(-7),
                $when,
                true,
                [
                    new NTAKPayment(
                        NTAKPaymentType::KESZPENZHUF(),
                        3201
                    )
                ],
                20
            )
        );

        $this->assertIsString($response);
        $this->assertIsArray($client->lastRequest());
        $this->assertIsArray($client->lastResponse());
    }
}
