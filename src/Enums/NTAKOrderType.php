<?php

namespace QueSoft\Ntak\Enums;

use MyCLabs\Enum\Enum;

final class NTAKOrderType extends Enum
{
    const NORMAL = 'Normál';
    const SZTORNO = 'Storno';
    const HELYESBITO = 'Helyesbítő';
}
