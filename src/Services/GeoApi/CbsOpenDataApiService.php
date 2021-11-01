<?php

namespace Vng\EvaCore\Services\GeoApi;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class CbsOpenDataApiService extends GeoApiService
{
    const FILENAME = 'cbs-opendata-areas';

    protected static function getFileName()
    {
        return static::FILENAME;
    }

    public static function fetchApiContents($includeGeo = false): string
    {
        $client = new Client(['base_uri' => 'https://opendata.cbs.nl/ODataApi/odata/']);
        $response = $client->request('GET', '84929NED/TypedDataSet', [
            'query' => [
                '$select' => 'RegioS,Code_1,Naam_2,Code_4,Naam_5',
            ]
        ]);

        return $response->getBody()->getContents();
    }

    public static function fetchApiData($includeGeo = false): array
    {
        $contents = static::fetchApiContents($includeGeo);
        $data = static::transformResponseToArray($contents);
        $data['value'] = static::transformValues($data['value']);
        return $data;
    }

    public static function getData($includeGeo = false): array
    {
        $data = parent::getData($includeGeo);
        $data['value'] = static::transformValues($data['value']);
        return $data;
    }

    public static function transformValues(array $data): array
    {
        return array_map(function($entry) {
            return array_map('trim', $entry);
        }, $data);
    }

    public static function getFormattedRegionData(array $data)
    {
        return array_map(function($entry) {
            return [
                'code' => $entry['Code_4'],
                'name' => $entry['Naam_5'],
                'slug' => (string) Str::slug($entry['Naam_5']),
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
            ];
        }, $data);
    }
}
