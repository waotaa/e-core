<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElastic;
use Vng\EvaCore\Jobs\SyncResourceToElastic;
use Vng\EvaCore\Models\Provider;
use Illuminate\Console\Command;

class SyncProviders extends Command
{
    protected $signature = 'elastic:sync-providers {--f|fresh}';
    protected $description = 'Sync all providers to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing providers');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'providers', '--force' => true]);
        }

        $this->output->writeln('');
        foreach (Provider::all() as $provider) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $provider->name);
            dispatch(new SyncResourceToElastic($provider));
        }

        foreach (Provider::onlyTrashed()->get() as $provider) {
            dispatch(new RemoveResourceFromElastic($provider));
        }

        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
