<?php

namespace Vng\EvaCore\Helpers;

use Illuminate\Support\Facades\File;

class Codelijsten
{
    public static function get(string $listName)
    {
        $path = __DIR__ . "/../../resources/codelijsten/{$listName}.php";
//        $path = resource_path("codelijsten/{$listName}.php");

        if (File::exists($path)) {
            return include $path;
        }

        return [];
    }

    public static function getName(string $listName, ?string $code = null): ?string
    {
        if (is_null($code)) {
            return null;
        }
        $list = self::get($listName);
        return $list[$code] ?? null;
    }

    // Careful with werklandschap tegels, has non-unique names
    public static function getCode(string $listName, ?string $name = null): ?string
    {
        if (is_null($name)) {
            return null;
        }
        $list = self::get($listName);
        $list = array_flip($list);
        return $list[$name] ?? null;
    }

    public static function getArbeidsmarktregioName(?string $key = null): ?string
    {
        return self::getName('Arbeidsmarktregios', $key);
    }

    public static function getBereikName(?string $key = null): ?string
    {
        return self::getName('Bereik', $key);
    }

    public static function getBereikCode(?string $key = null): ?string
    {
        return self::getCode('Bereik', $key);
    }

    public static function getDienstverbandName(?string $key = null): ?string
    {
        return self::getName('Dienstverbanden', $key);
    }

    public static function getDoelgroepName(?string $key = null): ?string
    {
        return self::getName('Doelgroepen', $key);
    }

    public static function getDoelgroepenDennis(): array
    {
        $doelgroepen = self::get('Doelgroepen');
        return array_filter($doelgroepen, function($key) {
            return str_starts_with($key, 'DD');
        }, ARRAY_FILTER_USE_KEY);
    }

    public static function getDoelgroepenEva(): array
    {
        $doelgroepen = self::get('Doelgroepen');
        return array_filter($doelgroepen, function($key) {
            return str_starts_with($key, 'ED');
        }, ARRAY_FILTER_USE_KEY);
    }

    public static function getGroepsvormName(?string $key = null): ?string
    {
        return self::getName('Groepsvormen', $key);
    }

    public static function getJaNeeIndicatieName(?string $key = null): ?string
    {
        return self::getName('StdIndJN', $key);
    }

    public static function getJaNeeIndicatieCode(?bool $bool): ?string
    {
        $naam = $bool ? 'Ja' : 'Nee';
        return self::getCode('StdIndJN', $naam);
    }

    public static function getJaNeeNvtIndicatieName(?string $key = null): ?string
    {
        return self::getName('StdIndNvt', $key);
    }

    public static function getJaNeeNvtIndicatieCode(?bool $bool = null): ?string
    {
        $naam = 'Niet van toepassing';
        if (!is_null($bool)) {
            $naam = $bool ? 'Ja' : 'Nee';
        }
        return self::getCode('StdIndNvt', $naam);
    }

    public static function getKlantkenmerkName(?string $key = null): ?string
    {
        return self::getName('Klantkenmerken', $key);
    }

    public static function getLeeftijdsgroepName(?string $key = null): ?string
    {
        return self::getName('Leeftijdsgroepen', $key);
    }

    public static function getSectorName(?string $key = null): ?string
    {
        return self::getName('Sectoren', $key);
    }

    public static function getTrajectDuurEenheidName(?string $key = null): ?string
    {
        return self::getName('TrajectDuurEenheden', $key);
    }

    public static function getTrajectDuurEenheidCode(?string $key = null): ?string
    {
        return self::getCode('TrajectDuurEenheden', $key);
    }

    public static function getTypeContactPersoonRelatieName(?string $key = null): ?string
    {
        return self::getName('TypeContactPersoonRelaties', $key);
    }

    public static function getTypeContactPersoonRelatieCode(?string $key = null): ?string
    {
        return self::getCode('TypeContactPersoonRelaties', $key);
    }

    public static function getTypeGebiedName(?string $key = null): ?string
    {
        return self::getName('TypeGebieden', $key);
    }

    public static function getTypeGebiedCode(?string $key = null): ?string
    {
        return self::getCode('TypeGebieden', $key);
    }

    public static function getTypeOrganisatieName(?string $key = null): ?string
    {
        return self::getName('TypeOrganisaties', $key);
    }

    public static function getTypeOrganisatieCode(?string $key = null): ?string
    {
        return self::getCode('TypeOrganisaties', $key);
    }

    public static function getUitvoeringLocatieName(?string $key = null): ?string
    {
        return self::getName('TypeUitvoeringsLocaties', $key);
    }

    public static function getUitvoeringLocatieCode(?string $key = null): ?string
    {
        return self::getCode('TypeUitvoeringsLocaties', $key);
    }

    public static function getUitvoeringsVormName(?string $key = null): ?string
    {
        return self::getName('TypeUitvoeringsVormen', $key);
    }

    public static function getWerklandschapTegelName(?string $key = null): ?string
    {
        return self::getName('WerklandschapTegels', $key);
    }

    public static function getWerklandschapTegelsDennis(): array
    {
        $doelgroepen = self::get('WerklandschapTegels');
        return array_filter($doelgroepen, function($key) {
            return str_starts_with($key, 'DW');
        }, ARRAY_FILTER_USE_KEY);
    }

    public static function getWerklandschapTegelsEva(): array
    {
        $doelgroepen = self::get('WerklandschapTegels');
        return array_filter($doelgroepen, function($key) {
            return str_starts_with($key, 'EW');
        }, ARRAY_FILTER_USE_KEY);
    }
}