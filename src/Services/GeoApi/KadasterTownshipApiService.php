<?php

namespace Vng\EvaCore\Services\GeoApi;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class KadasterTownshipApiService extends NationalGeoRegisterApiService
{
    const FILENAME = 'kadaster-townships';

    public static function getFileName()
    {
        return self::FILENAME;
    }

    public static function fetchApiContents($includeGeo = false): string
    {
        $properties = [
            'code',
            'gemeentenaam'
        ];
        if ($includeGeo) {
            $properties[] = 'geom';
        }

        $client = new Client(['base_uri' => 'https://geodata.nationaalgeoregister.nl']);
        $response = $client->request('GET', 'bestuurlijkegrenzen/wfs', [
            'query' => array_merge(self::getDefaultQueryParameters(), [
                'typeName' => 'gemeenten',
                'propertyName' => join(',', $properties),
            ])
        ]);

        return $response->getBody()->getContents();
    }

    public static function getFormattedData(array $data): array
    {
        return array_map(function($entry) {
            return [
                'code' => 'GM' . $entry['properties']['code'],
                'name' => $entry['properties']['gemeentenaam'],
                'slug' => (string) Str::slug($entry['properties']['gemeentenaam']),
            ];
        }, $data);
    }
}
