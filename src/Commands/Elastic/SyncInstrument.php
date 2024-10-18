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

class SyncInstrument extends Command
{
    protected $signature = 'elastic:sync-instrument {instrumentId}';
    protected $description = 'Sync an instruments to ES';

    public function handle(): int
    {
        $this->output->writeln('syncing instrument...');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));
        $this->output->writeln('');

        $instrumentId = $this->argument('instrumentId');

        $index = 'instruments';
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $index = $prefix . '-' . $index;
        }

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        /** @var Instrument $instrument */
        $instrument = $instrumentRepository
            ->getElasticResourceBuilder()
            ->findOrFail($instrumentId);

        $this->output->writeln('');
        $this->getOutput()->write('- ' . $instrument->name);

        $attempt = new SyncAttempt();
        $attempt->action = 'sync';
        $attempt->resource()->associate($instrument);
        $attempt->save();

        dispatch(new SyncSearchableModelToElasticJob($instrument, $attempt));

        $this->output->newLine(2);
        $this->output->writeln('syncing instrument finished!');
        return 0;
    }
}
