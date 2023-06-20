<?php

namespace QueSoft\Ntak\Enums;

use MyCLabs\Enum\Enum;
use QueSoft\Ntak\Traits\EnumToArray;

final class NTAKCategory Extends Enum
{
    use EnumToArray;

    const ETEL = 'Étel';
    const ALKMENTESITAL_HELYBEN = 'Helyben készített alkoholmentes ital';
    const ALKMENTESITAL_NEM_HELYBEN = 'Nem helyben készített alkoholmentes ital';
    const ALKOHOLOSITAL = 'Alkoholos Ital';
    const EGYEB = 'Egyéb';

    /**
     * subcategories
     *
     * @return array|NTAKSubcategory[]
     */
    public function subcategories(): array
    {
        switch ($this) {
            case NTAKCategory::ETEL():
                return [
                    NTAKSubcategory::REGGELI(),
                    NTAKSubcategory::SZENDVICS(),
                    NTAKSubcategory::ELOETEL(),
                    NTAKSubcategory::LEVES(),
                    NTAKSubcategory::FOETEL(),
                    NTAKSubcategory::KORET(),
                    NTAKSubcategory::SAVANYUSAG_SALATA(),
                    NTAKSubcategory::KOSTOLO(),
                    NTAKSubcategory::PEKSUTEMENY(),
                    NTAKSubcategory::DESSZERT(),
                    NTAKSubcategory::SNACK(),
                    NTAKSubcategory::FOETEL_KORETTEL(),
                    NTAKSubcategory::ETELCSOMAG(),
                    NTAKSubcategory::EGYEB(),
                ];
                break;
            case NTAKCategory::ALKMENTESITAL_HELYBEN():
                return [
                    NTAKSubcategory::VIZ(),
                    NTAKSubcategory::LIMONADE_SZORP_FACSART(),
                    NTAKSubcategory::ALKOHOLMENTES_KOKTEL(),
                    NTAKSubcategory::TEA_FORROCSOKOLADE(),
                    NTAKSubcategory::ITALCSOMAG(),
                    NTAKSubcategory::KAVE(),
                ];
                break;
            case NTAKCategory::ALKMENTESITAL_NEM_HELYBEN():
                return [
                    NTAKSubcategory::VIZ(),
                    NTAKSubcategory::ROSTOS_UDITO(),
                    NTAKSubcategory::SZENSAVAS_UDITO(),
                    NTAKSubcategory::SZENSAVMENTES_UDITO(),
                    NTAKSubcategory::SZENSAVAS_UDITO(),
                    NTAKSubcategory::SZENSAVMENTES_UDITO(),
                    NTAKSubcategory::ITALCSOMAG(),
                ];
                break;
            case NTAKCategory::ALKOHOLOSITAL():
                return [
                    NTAKSubcategory::KOKTEL(),
                    NTAKSubcategory::LIKOR(),
                    NTAKSubcategory::PARLAT(),
                    NTAKSubcategory::SOR(),
                    NTAKSubcategory::BOR(),
                    NTAKSubcategory::PEZSGO(),
                    NTAKSubcategory::ITALCSOMAG(),
                ];
                break;
            case NTAKCategory::EGYEB():
                return [
                    NTAKSubcategory::EGYEB(),
                    NTAKSubcategory::SZERVIZDIJ(),
                    NTAKSubcategory::BORRAVALO(),
                    NTAKSubcategory::KISZALLITASI_DIJ(),
                    NTAKSubcategory::NEM_VENDEGLATAS(),
                    NTAKSubcategory::KORNYEZETBARAT_CSOMAGOLAS(),
                    NTAKSubcategory::MUANYAG_CSOMAGOLAS(),
                    NTAKSubcategory::KEDVEZMENY(),
                ];
                break;
            default:
                return [];
                break;
        }
    }

    /**
     * hasSubcategory
     *
     * @param  NTAKSubcategory $subcategory
     * @return bool
     */
    public function hasSubcategory(NTAKSubcategory $subcategory): bool
    {
        $subcategories = $this->subcategories();

        foreach ($subcategories as $validSubcategory) {
            if ($validSubcategory === $subcategory) {
                return true;
            }
        }

        return false;
    }
}
