<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Models\Area;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;

class RegionService
{
    public static function createRegion(BasicRegionModel $regionData): Region
    {
        /** @var Region $region */
        $region = Region::query()->updateOrCreate([
            'code' => $regionData->getCode(),
        ], [
            'name' => $regionData->getName(),
            'color' => $regionData->getColor(),
        ]);

        static::connectTownshipsToRegion($region);

        Area::query()->firstOrCreate([
            'area_id' => $region->id,
            'area_type' => Region::class
        ]);

        return $region;
    }

    public static function updateRegion(BasicRegionModel $regionData): Region
    {
        /** @var Region $region */
        $region = Region::query()->where('code', $regionData->getCode())->firstOrFail();
        $region->fill([
            'name' => $regionData->getName(),
            'slug' => $regionData->getSlug(),
            'color' => $regionData->getColor(),
        ])->save();

        static::connectTownshipsToRegion($region);

        return $region;
    }

    public static function deleteRegion(Region $region)
    {
        return $region->delete();
    }

    public static function connectTownshipsToAllRegions(): void
    {
        $regions = Region::all();
        foreach ($regions as $region) {
            static::connectTownshipsToRegion($region);
        }
    }

    public static function connectTownshipsToRegion(Region $region): void
    {
        $regionTownships = Township::query()->where('region_code', $region->code)->get();
        $region->townships()->saveMany($regionTownships);
    }
}
