<?php

namespace Vng\EvaCore\Commands\Operations;

use Illuminate\Console\Command;

class SetupGeoData extends Command
{
    protected $signature = 'eva:setup-geo {--d|download}';
    protected $description = 'Run the geo setup commands';

    public function handle(): int
    {
        $this->call('geo:townships-create', [
            '--download' => $this->option('download'),
        ]);
        $this->call('geo:regions-create', [
            '--download' => $this->option('download'),
        ]);
        $this->call('geo:regions-assign');
        $this->call('elastic:sync-regions', [
            '--fresh' => true,
        ]);
        return 0;
    }
}
