<?php

namespace QueSoft\Ntak\Enums;

use MyCLabs\Enum\Enum;
use QueSoft\Ntak\Traits\EnumToArray;

final class NTAKSubcategory extends Enum
{
    use EnumToArray;

    const REGGELI = 'reggeli';
    const SZENDVICS = 'szendvics';
    const ELOETEL = 'előétel';
    const LEVES = 'leves';
    const FOETEL = 'főétel';
    const KORET = 'köret';
    const SAVANYUSAG_SALATA = 'savanyúság/saláta';
    const KOSTOLO = 'kóstolóétel, kóstolófalat';
    const PEKSUTEMENY = 'péksütemény, pékáru';
    const DESSZERT = 'desszert';
    const SNACK = 'snack';
    const FOETEL_KORETTEL = 'főétel körettel';
    const ETELCSOMAG = 'ételcsomag';
    const EGYEB = 'egyéb';
    const VIZ = 'víz';
    const LIMONADE_SZORP_FACSART = 'limonádé / szörp / frissen facsart ital';
    const ALKOHOLMENTES_KOKTEL = 'alkoholmentes koktél, alkoholmentes kevert ital';
    const TEA_FORROCSOKOLADE = 'tea, forrócsoki és egyéb tejalapú italok';
    const ITALCSOMAG = 'italcsomag';
    const KAVE = 'kávé';
    const ROSTOS_UDITO = 'rostos üdítő';
    const SZENSAVAS_UDITO = 'szénsavas üdítő';
    const SZENSAVMENTES_UDITO = 'szénsavmentes üdítő ';
    const KOKTEL = 'koktél, kevert ital';
    const LIKOR = 'likőr';
    const PARLAT = 'párlat';
    const SOR = 'sör';
    const BOR = 'bor';
    const PEZSGO = 'pezsgő';
    const SZERVIZDIJ = 'szervizdíj';
    const BORRAVALO = 'borravaló';
    const KISZALLITASI_DIJ = 'kiszállítási díj';
    const NEM_VENDEGLATAS = 'nem vendéglátás';
    const KORNYEZETBARAT_CSOMAGOLAS = 'környezetbarát csomagolás';
    const MUANYAG_CSOMAGOLAS = 'műanyag csomagolás';
    const KEDVEZMENY = 'kedvezmény';
}
