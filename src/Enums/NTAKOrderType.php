<?php

namespace Natsu007\Ntak\Enums;

use MyCLabs\Enum\Enum;

final class NTAKOrderType extends Enum
{
    const NORMAL = 'Normál';
    const SZTORNO = 'Storno';
    const HELYESBITO = 'Helyesbítő';
}
