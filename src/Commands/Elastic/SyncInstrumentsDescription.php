<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\ElasticResources\Instrument\InstrumentDescriptionResource;
use Vng\EvaCore\Jobs\RemoveResourceFromElastic;
use Vng\EvaCore\Jobs\SyncCustomResourceToElastic;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;

class SyncInstrumentsDescription extends Command
{
    protected $signature = 'elastic:sync-instruments-description {--f|fresh}';
    protected $description = 'Sync all instruments public description to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing instruments description');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'instruments_description', '--force' => true]);
        }

        $this->output->writeln('');
        foreach (Instrument::all() as $instrument) {
            $this->getOutput()->write('.');
            dispatch(new SyncCustomResourceToElastic(
                $instrument,
                'instruments_description',
                InstrumentDescriptionResource::make($instrument),
            ));
        }

        foreach (Instrument::onlyTrashed()->get() as $instrument) {
            dispatch(new RemoveResourceFromElastic('instruments_description', $instrument->getSearchId()));
        }

        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
