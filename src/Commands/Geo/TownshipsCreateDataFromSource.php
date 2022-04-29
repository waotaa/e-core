<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoData\BasicTownshipModel;
use Vng\EvaCore\Services\GeoData\TownshipDataService;
use Vng\EvaCore\Services\GeoData\TownshipService;
use Illuminate\Console\Command;

class TownshipsCreateDataFromSource extends Command
{
    protected $signature = 'geo:townships-create {--d|download}';
    protected $description = 'Create townships database entries from the data source file';

    public function handle(): int
    {
        $this->getOutput()->writeln('create township data from source data..');
        $this->output->writeln('');

        if ($this->option('download')) {
            $townshipData = TownshipDataService::loadOrCreateSourceData();
        } else {
            $townshipData = TownshipDataService::loadSourceData();
        }

        $sourceData = TownshipDataService::createBasicGeoCollectionFromData($townshipData);
        $sourceData->each(function (BasicTownshipModel $townshipModel) {
            $this->output->write('.');
            TownshipService::createTownship($townshipModel);
        });

        $this->output->writeln('');
        $this->getOutput()->writeln('create township data from source data finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
