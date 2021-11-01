<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Models\Area;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ImportGeoData extends Command
{
    protected $signature = 'eva:import-geo';
    protected $description = 'Import region and locale data from file';

    public function handle(): int
    {
        exit('depricated');

        $this->output->warning('This command does not remove gemeenten who are no longer in the geo.json');
        // If you need to remove gemeenten you should do it manually after moving or removing all instruments from them

        try {
            $storage = Storage::disk('local');
            if (!App::environment('local')) {
                $storage = Storage::disk('s3');
            }
            $geoDataString = $storage->get('fixed/geo.json');
        } catch (FileNotFoundException $e) {
            exit('file not found');
        }
        $geoData = json_decode($geoDataString, true);

        usort($geoData, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        foreach ($geoData as $regionData) {
            $this->output->writeln('>>> ' . $regionData['name']);

            /** @var Region $region */
            $region = Region::query()->where('name', $regionData['name'])->firstOrCreate([
                'name' => $regionData['name'],
            ], [
                'color' => $this->pickRandomColor(),
            ]);

            Area::query()->where(['area_id' => $region->id, 'area_type' => Region::class])->firstOrCreate([
                'area_id' => $region->id,
                'area_type' => Region::class,
            ]);

            usort($regionData['gemeenten'], function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            foreach ($regionData['gemeenten'] as $gemeenteData) {
                $this->output->writeln($gemeenteData['name']);

                $gemeente = Township::query()->where(['region_id' => $region->id, 'name' => $gemeenteData['name']])->firstOrCreate([
                    'region_id' => $region->id,
                    'name' => $gemeenteData['name'],
                    'limits' => $gemeenteData['limits'],
                ]);

                $gemeente->update([
                    'limits' => $gemeenteData['limits'],
                ]);

                Area::query()->where(['area_id' => $gemeente->id, 'area_type' => Township::class])->firstOrCreate([
                    'area_id' => $gemeente->id,
                    'area_type' => Township::class,
                ]);
            }
        }

        return 0;
    }

    private function pickRandomColor(): string
    {
        $colors = new Collection([
            'cc0033',
            'd11a47',
            'd6335c',
            'db4d70',
            'e06685',
            'e68099',
            'eb99ad',
            'f0b3c2',
            'f5ccd6',
            'fae6eb'
        ]);
        return '#' . $colors->random();
    }
}
