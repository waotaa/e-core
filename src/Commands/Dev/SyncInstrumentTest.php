<?php

namespace Vng\EvaCore\Commands\Dev;

use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class SyncInstrumentTest extends Command
{
    use LogsDatabaseQueries;

    protected $signature = 'dev:sync-instrument-test {instrument}';
    protected $description = 'Sync specific instrument to ES';

    public function __construct()
    {
        parent::__construct();
        $this->registerQueryListener();
    }


    public function handle(): int
    {
        $this->output->writeln('starting sync instruments test...');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));

        $instrumentId = $this->argument('instrument');

        $this->output->writeln('');

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        /** @var Instrument $instrument */
        $instrument = $instrumentRepository
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
            ->where('id', $instrumentId)
            ->firstOrFail();

        $attempt = new SyncAttempt();
        $attempt->action = 'sync-test';
        $attempt->resource()->associate($instrument);
        $attempt->save();

        dispatch(new SyncSearchableModelToElasticJob($instrument, $attempt));

        $this->output->newLine(2);
        $this->output->writeln('sync instruments test finished!');
        return 0;
    }
}
