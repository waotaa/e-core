<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Support\Facades\Bus;
use Vng\EvaCore\Jobs\FetchNewInstrumentRatingsJob;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncInstruments extends Command
{
    protected $signature = 'elastic:sync-instruments {--f|fresh} {--p|pure}';
    protected $description = 'Sync all instruments to ES';

    public function handle(): int
    {
        $this->output->writeln('syncing instruments...');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'instruments', '--force' => true]);
        }

        $this->output->writeln('');

        $index = 'instruments';
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $index = $prefix . '-' . $index;
        }
        if (!ElasticsearchEndpointService::make()->indexExists($index)) {
            $this->call(CreateIndex::class, [
                'index' => 'instruments'
            ]);
        }

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instruments = $instrumentRepository
            ->getElasticResourceBuilder()
            ->get();

        $this->output->writeln($instruments->count() . ' instruments found');
        $this->output->writeln('');

        foreach ($instruments as $instrument) {
            $this->output->write('.');
//            $this->getOutput()->write('- ' . $instrument->name);

            $attempt = new SyncAttempt();
            $attempt->action = 'sync';
            $attempt->resource()->associate($instrument);
            $attempt->save();

            $jobs = [];
            // If not pure, then fetch rating first
            if (!$this->option('pure')) {
                $jobs[] = new FetchNewInstrumentRatingsJob($instrument);
            }
            $jobs[] = new SyncSearchableModelToElasticJob($instrument, $attempt);
            Bus::chain($jobs)->dispatch();
        }

        foreach (Instrument::onlyTrashed()->get() as $instrument) {
            dispatch(new RemoveResourceFromElasticJob($instrument->getSearchIndex(), $instrument->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing instruments finished!');
        return 0;
    }
}
