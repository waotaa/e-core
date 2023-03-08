<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Vng\EvaCore\Models\SyncAttempt;

class SyncInstruments extends Command
{
    protected $signature = 'elastic:sync-instruments {--f|fresh}';
    protected $description = 'Sync all instruments to ES';

    public function handle(): int
    {
        $this->output->info('fetch ratings first');
        $this->call(FetchNewInstrumentRatings::class);

        $this->output->writeln('syncing instruments...');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'instruments', '--force' => true]);
        }

        $this->output->writeln('');
        foreach (Instrument::all() as $instrument) {
            $this->output->write('.');
//            $this->getOutput()->write('- ' . $instrument->name);

            $attempt = new SyncAttempt();
            $attempt->action = 'sync';
            $attempt->resource()->associate($instrument);
            $attempt->save();

            dispatch(new SyncSearchableModelToElasticJob($instrument, $attempt));
        }

        foreach (Instrument::onlyTrashed()->get() as $instrument) {
            dispatch(new RemoveResourceFromElasticJob($instrument->getSearchIndex(), $instrument->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing instruments finished!');
        return 0;
    }
}
