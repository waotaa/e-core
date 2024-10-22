<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\ElasticResources\SGR\InstrumentResource;
use Vng\EvaCore\ElasticResources\SGR\ProviderResource;
use Vng\EvaCore\Jobs\SyncResourceToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncForSgr extends Command
{
    protected $signature = 'elastic:sync-sgr {--f|fresh}';
    protected $description = 'Sync sgr entities to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing...');
        $this->getOutput()->writeln('_ index prefix: ' . $this->getIndexPrefix());

        $this->syncInstruments();

        $this->syncProviders();

        $this->getOutput()->writeln('syncing finished!');

        return 0;
    }

    public function getIndexPrefix(): string
    {
        return 'sgr-';
    }

    public function syncInstruments()
    {
        $this->getOutput()->write('_ instruments: ');

        if ($this->option('fresh')) {
            $indexPrefix = $this->getIndexPrefix();
            $instrumentIndex = $indexPrefix . (new Instrument())->getSearchIndex();
            $this->call('elastic:delete-index', ['index' => $instrumentIndex, '--force' => true]);
        }

        $instrumentIndex = $this->getIndexPrefix() . (new Instrument())->getSearchIndex();
        if (!ElasticsearchEndpointService::make()->indexExists($instrumentIndex)) {
            $this->call(CreateIndex::class, [
                'index' => $instrumentIndex,
            ]);
        }

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instruments = $instrumentRepository
            ->getElasticResourceBuilder()
            ->get();

        $this->getOutput()->writeln($instruments->count() . ' instruments found');
        $this->getOutput()->writeln('');

        foreach ($instruments as $instrument) {
            $this->getOutput()->write('.');
            $this->syncInstrument($instrument, $instrumentIndex);
        }
        $this->getOutput()->newLine();
    }

    public function syncInstrument(Instrument $instrument, string $instrumentIndex)
    {
        $attempt = new SyncAttempt();
        $attempt->action = 'sync-sgr';
        $attempt->resource()->associate($instrument);
        $attempt->save();

        dispatch(new SyncResourceToElasticJob(
            $instrument,
            $instrumentIndex,
            InstrumentResource::class,
            $attempt
        ));
    }

    public function syncProviders()
    {
        $this->output->write('_ providers: ');

        if ($this->option('fresh')) {
            $indexPrefix = $this->getIndexPrefix();
            $providerIndex = $indexPrefix . (new Provider())->getSearchIndex();
            $this->call('elastic:delete-index', ['index' => $providerIndex, '--force' => true]);
        }

        $providerIndex = $this->getIndexPrefix() . (new Provider())->getSearchIndex();
        if (!ElasticsearchEndpointService::make()->indexExists($providerIndex)) {
            $this->call(CreateIndex::class, [
                'index' => $providerIndex,
                '--exact' => true
            ]);
        }

        /** @var ProviderRepositoryInterface $providerRepository */
        $providerRepository = app(ProviderRepositoryInterface::class);
        $providers = $providerRepository
            ->builder()
            ->with([
                'organisation',
                'address',
                'contacts'
            ])
            ->get();

        $this->getOutput()->writeln($providers->count() . ' providers found');
        $this->getOutput()->writeln('');

        foreach ($providers as $provider) {
            $this->output->write('.');
            $this->syncProvider($provider, $providerIndex);
        }
        $this->output->newLine();
    }

    public function syncProvider(Provider $provider, string $providerIndex)
    {
        $attempt = new SyncAttempt();
        $attempt->action = 'sync-sgr';
        $attempt->resource()->associate($provider);
        $attempt->save();

        dispatch(new SyncResourceToElasticJob(
            $provider,
            $providerIndex,
            ProviderResource::class,
            $attempt
        ));
    }
}
