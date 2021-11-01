<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Services\GeoData\TownshipDataService;
use Illuminate\Console\Command;

class TownshipsCreateDataSetFromApi extends Command
{
    protected $signature = 'geo:townships-create-dataset {--update-source}';
    protected $description = 'create our township dataset from different sources';

    public function handle(): int
    {
        $this->output->writeln('creating township dataset..');
        $this->output->writeln('');

        $updateSource = $this->option('update-source');
        if ($updateSource) {
            $this->info('Please create and check a snapshot before updating the general dataset');
            if ($this->confirm('Are you sure you want to overwrite the general dataset?')) {
                $this->output->writeln('updating general dataset');
                $this->output->writeln('');
                TownshipDataService::createSourceData();
                return 0;
            } else {
                $this->output->writeln('exiting script');
            }
        }

        $this->output->writeln('creating dataset snapshot');
        $this->output->writeln('');
        TownshipDataService::createDataSnapshot();

        $this->output->writeln('creating township dataset finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
