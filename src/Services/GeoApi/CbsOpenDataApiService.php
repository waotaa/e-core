<?php

namespace Vng\EvaCore\Services\GeoApi;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Vng\EvaCore\Services\GeoData\RegionDataService;

class CbsOpenDataApiService extends GeoApiService
{
    const FILENAME = 'cbs-opendata-areas';

    protected static function getFileName()
    {
        return static::FILENAME;
    }

    public static function fetchApiContents($includeGeo = false): string
    {
        // 84929NED (2021)
        // 85067NED (2022)
        // 85385NED (2023)
        $client = new Client(['base_uri' => 'https://opendata.cbs.nl/ODataApi/odata/']);
        $response = $client->request('GET', '85385NED/TypedDataSet', [
            'query' => [
                '$select' => 'RegioS,Code_1,Naam_2,Code_4,Naam_5',
            ]
        ]);

        return $response->getBody()->getContents();
    }

    public static function getData($includeGeo = false, $allowFromCache = false): array
    {
        $data = parent::getData($includeGeo, $allowFromCache);
        return static::cleanValues($data['value']);
    }

    public static function cleanValues(array $data): array
    {
        return array_map(function($entry) {
            return array_map('trim', $entry);
        }, $data);
    }

    public static function getRegionsFromData(array $cbsAreaData)
    {
        return collect($cbsAreaData)->unique('Code_4')->toArray();
    }

    public static function getFormattedRegionData(array $data)
    {
        return array_map(function($entry) {
            return [
                'code' => $entry['Code_4'],
                'name' => $entry['Naam_5'],
                'slug' => (string) Str::slug($entry['Naam_5']),
                'color' => RegionDataService::getRegionColor($entry['Code_4']),
            ];
        }, $data);
    }

    public static function getFormattedTownshipData(array $data)
    {
        return array_map(function($entry) {
            return [
                'code' => $entry['Code_1'],
                'name' => $entry['Naam_2'],
                'slug' => (string) Str::slug($entry['Naam_2']),
                'region_code' => $entry['Code_4'],
            ];
        }, $data);
    }
}
