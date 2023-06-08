<?php

namespace Natsu007\Ntak\Enums;

use MyCLabs\Enum\Enum;
use Natsu007\Ntak\Traits\EnumToArray;

final class NTAKPaymentType extends Enum
{
    use EnumToArray;

    const KESZPENZHUF = 'Készpénz huf';
    const KESZPENZEUR = 'Készpénz eur';
    const SZEPKARTYA = 'Szépkártya';
    const BANKKARTYA = 'Bankkártya';
    const ATUTALAS = 'Átutalás';
    const EGYEB = 'Egyéb';
    const VOUCHER = 'Voucher';
    const SZOBAHITEL = 'Szobahitel';
    const KEREKITES = 'Kerekítés';
}
