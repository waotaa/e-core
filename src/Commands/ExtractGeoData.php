<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExtractGeoData extends Command
{
    protected $signature = 'eva:extract-geo';
    protected $description = 'Extract and save a geo.json to storage from the database';

    public function handle(): int
    {
        exit('depricated');

        $regions = Region::query()->with(['gemeenten' => function ($q) {
            return $q->orderBy('naam');
        }])->orderBy('naam')->get();

        $geoData = [];

        $this->output->writeln('gathering data');

        foreach ($regions as $region) {
            $this->output->writeln('. ' . $region['naam']);
            if ($region['naam'] === 'Landelijk') {
                continue;
            }

            array_push($geoData, [
                'name' => $region['naam'],
                'gemeenten' => array_map(fn ($gemeente) => [
                    'name' => $gemeente['naam'],
                    'limits' => $gemeente['limits']
                ], $region->gemeenten->toArray())
            ]);
        }

        $this->output->writeln('');
        $this->output->writeln('storing json');

        Storage::disk('local')->put('fixed/geo.json', json_encode($geoData, JSON_PRETTY_PRINT));

        $this->output->writeln('finished');
        return 0;
    }
}
