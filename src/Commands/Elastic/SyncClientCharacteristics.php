<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\SyncAttempt;

class SyncClientCharacteristics extends Command
{
    protected $signature = 'elastic:sync-client-characteristics {--f|fresh}';
    protected $description = 'Sync all client characteristics to ES';

    public function handle(): int
    {
        $this->output->writeln('syncing client characteristics...');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'client_characteristics', '--force' => true]);
        }

        $this->output->writeln('');
        foreach (ClientCharacteristic::all() as $clientCharacteristic) {
            $this->output->write('.');
//            $this->getOutput()->write('- ' . $clientCharacteristic->name);

            $attempt = new SyncAttempt();
            $attempt->action = 'sync';
            $attempt->resource()->associate($clientCharacteristic);
            $attempt->save();

            dispatch(new SyncSearchableModelToElasticJob($clientCharacteristic, $attempt));
        }

        foreach (ClientCharacteristic::onlyTrashed()->get() as $clientCharacteristic) {
            dispatch(new RemoveResourceFromElasticJob($clientCharacteristic->getSearchIndex(), $clientCharacteristic->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing client characteristics finished!');
        return 0;
    }
}

