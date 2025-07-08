<?php

// TODO: Replace hardcoded slugs with a dynamic list from the environment repository

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;
use Vng\EvaCore\Services\ElasticSearch\SyncAttemptFactory;

class SyncForApi extends Command
{
    use UsePrefixedIndex;

    protected $signature = 'elastic:sync-api {--f|fresh}';
    protected $description = 'Sync api entities to ES';

    protected $environmentSlugs = [
        'amsterdam',
        'den-haag',
        'friesland',
        'haaglanden',
        'haaglanden-acc',
        'orionis-walcheren',
        'orionis-walcheren-acc',
        'weenerxl',
        'west-brabant',
    ];

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing...');

        $environments = $this->findEnvironments();
        foreach ($environments as $environment) {
            $this->syncForEnvironment($environment);
        }

        $this->getOutput()->writeln('syncing finished!');

        return 0;
    }

    public function findEnvironments(): Collection|array
    {
        /** @var EnvironmentRepositoryInterface $envRepo */
        $envRepo = app(EnvironmentRepositoryInterface::class);
        return $envRepo->builder()->whereIn('slug', $this->environmentSlugs)->get();
    }

    public function syncForEnvironment(Environment $environment)
    {
        $this->output->info('Syncing for ' . $environment->name);

        $this->output->writeln('_ index prefix: ' . $this->getEnvironmentApiPrefix($environment));

        $orgIds = $this->getFeaturingOrgIds($environment);
        $this->output->writeln('_ featured orgs: ' . $orgIds->join(', '));

        $this->refreshIndexes($environment);

        $this->syncEnvironmentInstruments($environment);

        $this->syncEnvironmentProviders($environment);
    }

    public function refreshIndexes(Environment $environment)
    {
        if ($this->option('fresh')) {
            $environmentApiPrefix = $this->getEnvironmentApiPrefix($environment);

            $instrumentIndex = $environmentApiPrefix . (new Instrument())->getSearchIndex();
            $this->call(DeleteIndex::class, [
                'index' => $instrumentIndex,
                '--force' => true
            ]);

            $prefixedInstrumentIndex = $this->prefixIndex($instrumentIndex);
            if (!ElasticsearchEndpointService::make()->indexExists($prefixedInstrumentIndex)) {
                $this->call(CreateIndex::class, [
                    'index' => $instrumentIndex
                ]);
            }

            $providerIndex = $environmentApiPrefix . (new Provider())->getSearchIndex();
            $this->call(DeleteIndex::class, [
                'index' => $providerIndex,
                '--force' => true
            ]);

            $prefixedProviderIndex = $this->prefixIndex($providerIndex);
            if (!ElasticsearchEndpointService::make()->indexExists($prefixedProviderIndex)) {
                $this->call(CreateIndex::class, [
                    'index' => $providerIndex
                ]);
            }
        }
    }

    public function syncEnvironmentInstruments(Environment $environment)
    {
        $this->output->write('_ instruments: ');
        $instruments = $this->getInstruments($environment);

        $index = $this->getEnvironmentApiPrefix($environment) . 'instruments';
        $delay = 0;
        $instruments->chunk(SyncBulkResourcesToElasticJob::BATCH_SIZE)->each(function ($instrumentsBatch) use ($index, &$delay) {
            $syncAttempt = SyncAttemptFactory::makeSyncAttempt(SyncAttempt::ACTION_INDEX)
                ->setNote('Instruments for API');
            $syncAttempt->save();

            dispatch(new SyncBulkResourcesToElasticJob(
                $instrumentsBatch,
                $index,
                Instrument::getResourceClass(),
                $syncAttempt
            ))->delay(now()->addSeconds($delay));

            // Verhoog de vertraging met 5 seconden voor de volgende iteratie, maar nooit meer dan 900
            $delay = min($delay + 5, 900);
        });

        $this->output->newLine();
    }

    public function syncEnvironmentProviders(Environment $environment)
    {
        $this->output->write('_ providers: ');
        $providers = $this->getProviders($environment);

        $index = $this->getEnvironmentApiPrefix($environment) . 'providers';
        $delay = 0;
        $providers->chunk(SyncBulkResourcesToElasticJob::BATCH_SIZE)->each(function ($providersBatch) use ($index, &$delay) {
            $syncAttempt = SyncAttemptFactory::makeSyncAttempt(SyncAttempt::ACTION_INDEX)
                ->setNote('Providers for API');
            $syncAttempt->save();

            dispatch(new SyncBulkResourcesToElasticJob(
                $providersBatch,
                $index,
                Provider::getResourceClass(),
                $syncAttempt
            ))->delay(now()->addSeconds($delay));

            // Verhoog de vertraging met 5 seconden voor de volgende iteratie, maar nooit meer dan 900
            $delay = min($delay + 5, 900);
        });

        $this->output->newLine();
    }

    public function getEnvironmentApiPrefix(Environment $environment): string
    {
        return 'api-'.$environment->getAttribute('slug').'-';
    }

    public function getFeaturingOrgIds(Environment $environment): \Illuminate\Support\Collection
    {
        return $environment->featuredOrganisations()->get()->pluck('id');
    }

    protected function getInstruments(Environment $environment): Collection|array
    {
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        return $instrumentRepository
//            ->getElasticResourceBuilder() // Maybe use this, only adds some relations, no removals
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
            ->whereIn('organisation_id', $this->getFeaturingOrgIds($environment))
            ->get();
    }

    protected function getProviders(Environment $environment): Collection|array
    {
        /** @var ProviderRepositoryInterface $providerRepository */
        $providerRepository = app(ProviderRepositoryInterface::class);
        return $providerRepository
            ->getElasticResourceBuilder()
            ->whereIn('organisation_id', $this->getFeaturingOrgIds($environment))
            ->get();
    }
}
