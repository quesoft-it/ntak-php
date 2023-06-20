<?php

namespace QueSoft\Ntak\Enums;

use MyCLabs\Enum\Enum;
use QueSoft\Ntak\Traits\EnumToArray;

final class NTAKDayType extends Enum
{
    use EnumToArray;

    const ADOTT_NAPON_ZARVA = 'Adott napon zárva';
    const FORGALOM_NELKULI_NAP = 'Forgalom nélküli nap';
    const NORMAL_NAP = 'Normál nap';
}
