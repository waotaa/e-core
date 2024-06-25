<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\ElasticResources\Instrument\InstrumentDescriptionResource;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncResourceToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

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

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instruments = $instrumentRepository
            ->builder()
            ->with([
                'organisation',
                'implementation',
                'groupForms',
                'locations',
                'registrationCodes',
                'ratings',
                'tiles',
                'targetGroups',
                'clientCharacteristics',
                'links',
                'videos',
                'downloads',
                'provider',
                'contacts',
                'availableRegions',
                'availableTownships',
                'availableNeighbourhoods',
                'parentInstrument'
            ])
            ->get();

        $this->output->writeln($instruments->count() . ' instruments found');
        $this->output->writeln('');

        foreach ($instruments as $instrument) {
            $this->getOutput()->write('.');

            $attempt = new SyncAttempt();
            $attempt->action = 'sync-description';
            $attempt->resource()->associate($instrument);
            $attempt->save();

            dispatch(new SyncResourceToElasticJob(
                $instrument,
                'instruments_description',
                InstrumentDescriptionResource::class,
                $attempt
            ));
        }

        foreach (Instrument::onlyTrashed()->get() as $instrument) {
            dispatch(new RemoveResourceFromElasticJob('instruments_description', $instrument->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing instruments description finished!');
        return 0;
    }
}
