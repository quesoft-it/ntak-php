<?php

namespace QueSoft\Ntak\Enums;

use MyCLabs\Enum\Enum;
use QueSoft\Ntak\Traits\EnumToArray;

final class NTAKAmount extends Enum
{
    use EnumToArray;

    const DARAB = 'darab';
    const LITER = 'liter';
    const KILOGRAMM = 'kilogramm';
    const EGYSEG = 'egyseg';
}
