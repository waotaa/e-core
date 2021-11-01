<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Services\GeoApi\CbsOpenDataApiService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TownshipDataService extends GeoDataService
{
    public static function fetchBaseData(): Collection
    {
        $data = CbsOpenDataApiService::getData();
        $cbsAreaData = CbsOpenDataApiService::transformValues($data['value']);
        return collect($cbsAreaData)->map(fn ($entry) => [
            'code' => $entry['Code_1'],
            'name' => $entry['Naam_2'],
            'slug' => (string) Str::slug($entry['Naam_2']),
            'region_code' => $entry['Code_4'],
        ])->values();
    }

    public static function createBasicGeoModelFromDataEntry(array $entry): BasicTownshipModel
    {
        $township = new BasicTownshipModel();
        $township
            ->setCode($entry['code'])
            ->setName($entry['name'])
            ->setSlug($entry['slug'])
            ->setRegionCode($entry['region_code'] ?? null);
        return $township;
    }

    protected static function getFileName(): string
    {
        return 'townships';
    }
}
