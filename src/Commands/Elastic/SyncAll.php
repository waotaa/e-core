<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;

class SyncAll extends Command
{
    protected $signature = 'elastic:sync-all {--f|fresh}';
    protected $description = 'Sync all searchable data to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing...');

        $this->output->writeln('used elastic instance: ' . config('elastic.cloud_id'));
        $this->output->writeln('used elastic username: ' . config('elastic.username'));

        $this->call('elastic:sync-environments', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-client-characteristics', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-instruments', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-instruments-description', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-news-items', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-professionals', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-providers', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-regions', ['--fresh' => $this->option('fresh')]);
//        $this->call('elastic:sync-themes', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-tiles', ['--fresh' => $this->option('fresh')]);
        $this->call('elastic:sync-townships', ['--fresh' => $this->option('fresh')]);

        return 0;
    }
}
