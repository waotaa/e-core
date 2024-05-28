<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Vng\EvaCore\Jobs\SyncResourceToElasticJob;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;

class SyncForApi extends Command
{
    protected $signature = 'elastic:sync-api {--f|fresh}';
    protected $description = 'Sync api entities to ES';

    protected $environmentSlugs = [
//        'local-env',
        'amsterdam'
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

        $this->output->writeln('_ index prefix: ' . $this->getIndexPrefix($environment));

        $orgIds = $this->getFeaturingOrgIds($environment);
        $this->output->writeln('_ featured orgs: ' . $orgIds->join(', '));

        $this->refreshIndexes($environment);

        $this->syncEnvironmentInstruments($environment);

        $this->syncEnvironmentProviders($environment);
    }

    public function refreshIndexes(Environment $environment)
    {
        if ($this->option('fresh')) {
            $indexPrefix = $this->getIndexPrefix($environment);
            $instrumentIndex = $indexPrefix . (new Instrument())->getSearchIndex();
            $this->call('elastic:delete-index', ['index' => $instrumentIndex, '--force' => true]);

            $providerIndex = $indexPrefix . (new Provider())->getSearchIndex();
            $this->call('elastic:delete-index', ['index' => $providerIndex, '--force' => true]);
        }
    }

    public function syncEnvironmentInstruments(Environment $environment)
    {
        $this->output->write('_ instruments: ');
        $instruments = $this->getInstruments($environment);
        foreach ($instruments as $instrument) {
            $this->output->write('.');
            $this->syncInstrument($instrument, $this->getIndexPrefix($environment));
        }
        $this->output->newLine();
    }

    public function syncEnvironmentProviders(Environment $environment)
    {
        $this->output->write('_ providers: ');
        $providers = $this->getProviders($environment);
        foreach ($providers as $provider) {
            $this->output->write('.');
            $this->syncProvider($provider, $this->getIndexPrefix($environment));
        }
        $this->output->newLine();
    }

    public function getIndexPrefix(Environment $environment): string
    {
        return 'api-'.$environment->getAttribute('slug').'-';
    }

    public function getFeaturingOrgIds(Environment $environment): \Illuminate\Support\Collection
    {
        return $environment->featuredOrganisations()->get()->pluck('id');
    }

    public function getInstruments(Environment $environment): Collection|array
    {
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        return $instrumentRepository
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

    public function getProviders(Environment $environment): Collection|array
    {
        /** @var ProviderRepositoryInterface $providerRepository */
        $providerRepository = app(ProviderRepositoryInterface::class);
        return $providerRepository
            ->builder()
            ->with([
                'organisation',
                'address',
                'contacts'
            ])
            ->whereIn('organisation_id', $this->getFeaturingOrgIds($environment))
            ->get();
    }

    public function syncInstrument(Instrument $instrument, string $indexPrefix)
    {
        $index = $indexPrefix . $instrument->getSearchIndex();
        dispatch(new SyncResourceToElasticJob($instrument, $index, $instrument->getResourceClass()));
    }

    public function syncProvider(Provider $provider, string $indexPrefix)
    {
        $index = $indexPrefix . $provider->getSearchIndex();
        dispatch(new SyncResourceToElasticJob($provider, $index, $provider->getResourceClass()));
    }
}
