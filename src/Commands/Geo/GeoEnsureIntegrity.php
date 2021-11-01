<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoData\RegionDataIntegrityService;
use Vng\EvaCore\Services\GeoData\TownshipDataIntegrityService;
use Illuminate\Console\Command;

class GeoEnsureIntegrity extends Command
{
    protected $signature = 'geo:integrity';
    protected $description = 'Checks the geo data integrity and fixes if needed';

    public function handle(): int
    {
        $this->output->writeln('making sure all regions have a code');
        RegionDataIntegrityService::checkAllRegionsHaveCode();
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('making sure all townships have region code');
        TownshipDataIntegrityService::checkAllTownshipsHaveRegionCode();
        $this->output->writeln('- checked');
        $this->output->writeln('');

        $this->output->writeln('checking townships codes are prefixed');
        TownshipDataIntegrityService::checkAllTownshipRegionCodesHavePrefix();
        $this->output->writeln('- checked');
        $this->output->writeln('');
        return 0;
    }
}
