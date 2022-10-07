<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;

class TownshipService
{
    public static function createTownship(BasicTownshipModel $townshipData): Township
    {
        return Township::withoutEvents(function() use ($townshipData) {
            /** @var Township $township */
            $township = Township::query()->updateOrCreate([
                'code' => $townshipData->getCode(),
            ], [
                'name' => $townshipData->getName(),
                'region_code' => $townshipData->getRegionCode(),
            ]);

            static::connectTownshipToRegion($township);
            return $township;
        });
    }

    public static function updateTownship(BasicTownshipModel $townshipData): Township
    {
        /** @var Township $township */
        $township = Township::query()->where('code', $townshipData->getCode())->firstOrFail();
        $township->fill([
            'name' => $townshipData->getName(),
            'slug' => $townshipData->getSlug(),
            'region_code' => $townshipData->getRegionCode(),
        ])->saveQuietly();
        static::connectTownshipToRegion($township);
        return $township;
    }

    public static function deleteTownship(Township $township)
    {
        return $township->delete();
    }

    public static function connectTownshipToRegion(Township $township): void
    {
        $region = Region::query()->where('code', $township->region_code)->first();
        if (is_null($region)) {
            return;
        }
        $township->region()->associate($region);
        $township->saveQuietly();
    }
}
