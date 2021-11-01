<?php

namespace Vng\EvaCore\Services\GeoApi;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class CbsDistrictNeighborhoodTownshipApiService extends NationalGeoRegisterApiService
{
    const FILENAME = 'cbs-district-neighborhood-townships';

    public static function getFileName()
    {
        return self::FILENAME;
    }

    public static function fetchApiContents($includeGeo = false): string
    {
        $properties = [
            'gemeentecode',
            'gemeentenaam'
        ];
        if ($includeGeo) {
            $properties[] = 'geom';
        }

        $client = new Client(['base_uri' => 'https://geodata.nationaalgeoregister.nl']);
        $response = $client->request('GET', 'wijkenbuurten2020/wfs', [
            'query' => array_merge(NationalGeoRegisterApiService::getDefaultQueryParameters(), [
                'typeName' => 'gemeenten2020',
                'propertyName' => join(',', $properties)
            ])
        ]);

        return $response->getBody()->getContents();
    }

    public static function getFormattedData(array $data)
    {
        return array_map(function($entry) {
            return [
                'code' => $entry['properties']['gemeentecode'],
                'name' => $entry['properties']['gemeentenaam'],
                'slug' => (string) Str::slug($entry['properties']['gemeentenaam']),
            ];
        }, $data);
    }
}
