<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;

class RegionService
{
    public static function createRegion(BasicRegionModel $regionData): Region
    {
        return Region::withoutEvents(function() use ($regionData) {
            /** @var Region $region */
            $region = Region::query()->firstOrNew([
                'code' => $regionData->getCode(),
            ], [
                'name' => $regionData->getName(),
                'slug' => $regionData->getSlug(),
                'color' => $regionData->getColor(),
            ]);
            $region->saveQuietly();
            static::connectTownshipsToRegion($region);
            return $region;
        });
    }

    public static function updateRegion(BasicRegionModel $regionData): Region
    {
        /** @var Region $region */
        $region = Region::query()->where('code', $regionData->getCode())->firstOrFail();
        $region->fill([
            'name' => $regionData->getName(),
            'slug' => $regionData->getSlug(),
            'color' => $regionData->getColor(),
        ])->saveQuietly();
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
