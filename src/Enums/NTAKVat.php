<?php

namespace QueSoft\Ntak\Enums;

use MyCLabs\Enum\Enum;
use QueSoft\Ntak\Traits\EnumToArray;

final class NTAKVat extends Enum
{
    use EnumToArray;

    const A_5 = '5%';
    const B_18 = '18%';
    const C_27 = '27%';
    const D_AJT = 'Ajt';
    const E_0 = '0%';
}
