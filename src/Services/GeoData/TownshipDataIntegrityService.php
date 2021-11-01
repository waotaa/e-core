<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Models\Township;
use Illuminate\Support\Collection;

class TownshipDataIntegrityService
{
    public static function checkAllTownshipsHaveRegionCode(): Collection
    {
        $noCodeTownships = Township::query()->with('region')->where('region_code', '')->get();
        $noCodeTownships->each(function(Township $township) {
            $region = $township->region;
            if (is_null($region)) {
                return;
            }
            $township->region_code = $region->code;
            $township->save();
        });
        return $noCodeTownships;
    }

    public static function checkAllTownshipRegionCodesHavePrefix(): Collection
    {
        $noPrefixTownships = Township::query()->where('code', 'not like', 'GM%')->get();
        $noPrefixTownships->each(function(Township $township) {
            $township->code = 'GM'.$township->code;
            $township->save();
        });
        return $noPrefixTownships;
    }
}
