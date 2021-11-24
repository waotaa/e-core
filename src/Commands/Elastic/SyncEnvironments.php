<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElastic;
use Vng\EvaCore\Jobs\SyncResourceToElastic;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;

class SyncEnvironments extends Command
{
    protected $signature = 'elastic:sync-environments {--f|fresh}';
    protected $description = 'Sync all environments to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing environments');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'environments', '--force' => true]);
        }

        $this->output->writeln('');
        foreach (Environment::all() as $environment) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $environment->name);
            dispatch(new SyncResourceToElastic($environment));
        }

        foreach (Environment::onlyTrashed()->get() as $environment) {
            dispatch(new RemoveResourceFromElastic($environment->getSearchIndex(), $environment->getSearchId()));
        }

        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
