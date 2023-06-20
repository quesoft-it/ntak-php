<?php

namespace QueSoft\Ntak\Enums;

use MyCLabs\Enum\Enum;
use QueSoft\Ntak\Traits\EnumToArray;

final class NTAKVerifyStatus extends Enum
{
    use EnumToArray;

    const BEFOGADVA = 'BEFOGADVA';
    const TELJESEN_HIBAS = 'TELJESEN_HIBAS';
    const RESZBEN_SIKERES = 'RESZBEN_SIKERES';
    const TELJESEN_SIKERES = 'TELJESEN_SIKERES';
    const UJRA_KULDENDO = 'UJRA_KULDENDO';
}
