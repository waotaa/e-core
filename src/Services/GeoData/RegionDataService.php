<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Services\GeoApi\CbsOpenDataApiService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RegionDataService extends GeoDataService
{
    public static function fetchApiData(): Collection
    {
        $data = CbsOpenDataApiService::getData();
        $data = CbsOpenDataApiService::getRegionsFromData($data);
        return collect(CbsOpenDataApiService::getFormattedRegionData($data))->values();
    }

    public static function fetchData(): Collection
    {
        $data = CbsOpenDataApiService::getData(false, true);
        $data = CbsOpenDataApiService::getRegionsFromData($data);
        return collect(CbsOpenDataApiService::getFormattedRegionData($data))->values();
    }

    public static function createBasicGeoModelFromDataEntry(array $entry): BasicRegionModel
    {
        $region = new BasicRegionModel();
        $region
            ->setCode($entry['code'])
            ->setName($entry['name'])
            ->setSlug($entry['slug'])
            ->setColor($entry['color'] ?? null);
        return $region;
    }

    protected static function getFileName(): string
    {
        return 'regions';
    }

    public static function getRegionColor(string $regionCode): string
    {
        $regionData = static::getRegions();
        $i = array_search($regionCode, array_column($regionData, 'code'));
        return $regionData[$i]['color'];
    }

    public static function findRegionByLegacySlug($slug)
    {
        return collect(static::getRegions())->firstWhere('legacy.slug', $slug);
    }

    public static function getRegions(): array
    {
        return [
            [
                'naam' => 'Groningen',
                'legacy' => [
                    'name' => 'Groningen',
                    'slug' => 'groningen'
                ],
                'code' => 'AM01',
                'color' => '#e06685',
            ],
            [
                'naam' => 'Friesland',
                'legacy' => [
                    'name' => 'Friesland',
                    'slug' => 'friesland'
                ],
                'code' => 'AM02',
                'color' => '#f5ccd6',
            ],
            [
                'naam' => 'Noord-Holland (Noord)',
                'legacy' => [
                    'name' => 'Noord-Holland (Noord)',
                    'slug' => 'noord-holland-noord'
                ],
                'code' => 'AM15',
                'color' => '#fae6eb',
            ],
            [
                'naam' => 'Drenthe',
                'legacy' => [
                    'name' => 'Drenthe',
                    'slug' => 'drenthe'
                ],
                'code' => 'AM03',
                'color' => '#cc0033'
            ],
            [
                'naam' => 'Zwolle',
                'legacy' => [
                    'name' => 'Zwolle',
                    'slug' => 'zwolle'
                ],
                'code' => 'AM36',
                'color' => '#e68099',
            ],
            [
                'naam' => 'Flevoland',
                'legacy' => [
                    'name' => 'Flevoland',
                    'slug' => 'flevoland'
                ],
                'code' => 'AM11',
                'color' => '#d11a47'
            ],
            [
                'naam' => 'Zaanstreek / Waterland',
                'legacy' => [
                    'name' => 'Zaanstreek / Waterland',
                    'slug' => 'zaanstreek-waterland'
                ],
                'code' => 'AM17',
                'color' => '#e06685'
            ],
            [
                'naam' => 'Zuid-Kennemerland en IJmond',
                'legacy' => [
                    'name' => 'Zuid-Kennemerland en IJmond',
                    'slug' => 'zuid-kennemerland-en-ijmond'
                ],
                'code' => 'AM37',
                'color' => '#d6335c'
//                'color' => '#d11a47'
            ],
            [
                'naam' => 'Twente',
                'legacy' => [
                    'name' => 'Twente',
                    'slug' => 'twente'
                ],
                'code' => 'AM05',
                'color' => '#fae6eb'
            ],
            [
                'naam' => 'Groot Amsterdam',
                'legacy' => [
                    'name' => 'Groot Amsterdam',
                    'slug' => 'groot-amsterdam'
                ],
                'code' => 'AM18',
                'color' => '#eb99ad'
            ],
            [
                'naam' => 'Stedendriehoek en Noordwest Veluwe',
                'legacy' => [
                    'name' => 'Stedendriehoek en Noordwest Veluwe',
                    'slug' => 'stedendriehoek-en-noordwest-veluwe'
                ],
                'code' => 'AM06',
                'color' => '#d6335c'
            ],
            [
                'naam' => 'Gooi- en Vechtstreek',
                'legacy' => [
                    'name' => 'Gooi- en Vechtstreek',
                    'slug' => 'gooi-en-vechtstreek'
                ],
                'code' => 'AM12',
                'color' => '#db4d70'
            ],
            [
                'naam' => 'Holland Rijnland',
                'legacy' => [
                    'name' => 'Holland Rijnland',
                    'slug' => 'holland-rijnland'
                ],
                'code' => 'AM19',
                'color' => '#d6335c'
//                'color' => '#e68099'
            ],
            [
                'naam' => 'Midden-Utrecht',
                'legacy' => [
                    'name' => 'Midden-Utrecht',
                    'slug' => 'midden-utrecht'
                ],
                'code' => 'AM13',
                'color' => '#e06685'
            ],
            [
                'naam' => 'Amersfoort',
                'legacy' => [
                    'name' => 'Amersfoort',
                    'slug' => 'amersfoort'
                ],
                'code' => 'AM14',
                'color' => '#fae6eb'
            ],
            [
                'naam' => 'Food Valley',
                'legacy' => [
                    'name' => 'Food Valley',
                    'slug' => 'food-valley'
                ],
                'code' => 'AM38',
                'color' => '#db4d70'
//                'color' => '#d11a47'
            ],
            [
                'naam' => 'Achterhoek',
                'legacy' => [
                    'name' => 'Achterhoek',
                    'slug' => 'achterhoek'
                ],
                'code' => 'AM09',
                'color' => '#eb99ad'
            ],
            [
                'naam' => 'Zuid-Holland Centraal',
                'legacy' => [
                    'name' => 'Zuid-Holland Centraal',
                    'slug' => 'zuid-holland-centraal'
                ],
                'code' => 'AM34',
                'color' => '#d2798f'
//                'color' => '#d11a47'
            ],
            [
                'naam' => 'Midden-Holland',
                'legacy' => [
                    'name' => 'Midden-Holland',
                    'slug' => 'midden-holland'
                ],
                'code' => 'AM20',
                'color' => '#cc0033'
            ],
            [
                'naam' => 'Haaglanden',
                'legacy' => [
                    'name' => 'Haaglanden',
                    'slug' => 'haaglanden'
                ],
                'code' => 'AM21',
                'color' => '#cc0033'
            ],
            [
                'naam' => 'Midden-Gelderland',
                'legacy' => [
                    'name' => 'Midden-Gelderland',
                    'slug' => 'midden-gelderland'
                ],
                'code' => 'AM07',
                'color' => '#d2798f',
            ],
            [
                'naam' => 'Rijnmond',
                'legacy' => [
                    'name' => 'Rijnmond',
                    'slug' => 'rijnmond'
                ],
                'code' => 'AM22',
                'color' => '#e68099'
            ],
            [
                'naam' => 'Rivierenland',
                'legacy' => [
                    'name' => 'Rivierenland',
                    'slug' => 'rivierenland'
                ],
                'code' => 'AM10',
                'color' => '#f0b3c2'
            ],
            [
                'naam' => 'Gorinchem',
                'legacy' => [
                    'name' => 'Gorinchem',
                    'slug' => 'gorinchem'
                ],
                'code' => 'AM35',
                'color' => '#db4d70'
//                'color' => '#e68099'
            ],
            [
                'naam' => 'Rijk van Nijmegen',
                'legacy' => [
                    'name' => 'Rijk van Nijmegen',
                    'slug' => 'rijk-van-nijmegen'
                ],
                'code' => 'AM08',
                'color' => '#e68099'
            ],
            [
                'naam' => 'Drechtsteden',
                'legacy' => [
                    'name' => 'Drechtsteden',
                    'slug' => 'drechtsteden'
                ],
                'code' => 'AM23',
                'color' => '#f0b3c2'
            ],
            [
                'naam' => 'Noordoost-Brabant',
                'legacy' => [
                    'name' => 'Noordoost-Brabant',
                    'slug' => 'noordoost-brabant'
                ],
                'code' => 'AM27',
                'color' => '#d11a47'
            ],
            [
                'naam' => 'West-Brabant',
                'legacy' => [
                    'name' => 'West-Brabant',
                    'slug' => 'west-brabant'
                ],
                'code' => 'AM25',
                'color' => '#eb99ad'
//                'color' => '#d11a47'
            ],
            [
                'naam' => 'Zeeland',
                'legacy' => [
                    'name' => 'Zeeland',
                    'slug' => 'zeeland'
                ],
                'code' => 'AM24',
                'color' => '#f0b3c2'
            ],
            [
                'naam' => 'Midden-Brabant',
                'legacy' => [
                    'name' => 'Midden-Brabant',
                    'slug' => 'midden-brabant'
                ],
                'code' => 'AM26',
                'color' => '#d2798f'
//                'color' => '#e68099'
            ],
            [
                'naam' => 'Noord-Limburg',
                'legacy' => [
                    'name' => 'Noord-Limburg',
                    'slug' => 'noord-limburg'
                ],
                'code' => 'AM29',
                'color' => '#f5ccd6'
            ],
            [
                'naam' => 'Helmond-De Peel',
                'legacy' => [
                    'name' => 'Helmond-De Peel',
                    'slug' => 'helmond-de-peel'
                ],
                'code' => 'AM32',
                'color' => '#eb99ad'
            ],
            [
                'naam' => 'Zuidoost-Brabant',
                'legacy' => [
                    'name' => 'Zuidoost-Brabant',
                    'slug' => 'zuidoost-brabant'
                ],
                'code' => 'AM28',
                'color' => '#f5ccd6'
            ],
            [
                'naam' => 'Midden-Limburg',
                'legacy' => [
                    'name' => 'Midden-Limburg',
                    'slug' => 'midden-limburg'
                ],
                'code' => 'AM33',
                'color' => '#d11a47'
            ],
            [
                'naam' => 'Zuid-Limburg',
                'legacy' => [
                    'name' => 'Zuid-Limburg',
                    'slug' => 'zuid-limburg'
                ],
                'code' => 'AM30',
                'color' => '#f0b3c2'
            ]
        ];
    }
}
