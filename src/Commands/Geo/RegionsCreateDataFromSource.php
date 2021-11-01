<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoData\BasicRegionModel;
use Vng\EvaCore\Services\GeoData\RegionDataService;
use Vng\EvaCore\Services\GeoData\RegionService;
use Illuminate\Console\Command;

class RegionsCreateDataFromSource extends Command
{
    protected $signature = 'geo:regions-create {--d|download}';
    protected $description = 'Create region database entries from the data source file';

    public function handle(): int
    {
        $this->getOutput()->writeln('create region data from source data..');
        $this->output->writeln('');

        if ($this->option('download')) {
            $regionData = RegionDataService::loadOrCreateData();
        } else {
            $regionData = RegionDataService::loadData();
        }

        $sourceData = RegionDataService::createBasicGeoCollectionFromData($regionData);
        $sourceData->each(function (BasicRegionModel $regionModel) {
            $this->output->write('.');
            RegionService::createRegion($regionModel);
        });

        $this->output->writeln('');
        $this->getOutput()->writeln('create region data from source data finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
