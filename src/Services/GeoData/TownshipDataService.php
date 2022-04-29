<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Services\GeoApi\CbsOpenDataApiService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TownshipDataService extends GeoDataService
{
    public static function fetchApiData(): Collection
    {
        $data = CbsOpenDataApiService::getData();
        return collect(CbsOpenDataApiService::getFormattedTownshipData($data))->values();
    }

    // fetch from file or api
    public static function fetchData(): Collection
    {
        $data = CbsOpenDataApiService::getData(false, true);
        return collect(CbsOpenDataApiService::getFormattedTownshipData($data))->values();
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
