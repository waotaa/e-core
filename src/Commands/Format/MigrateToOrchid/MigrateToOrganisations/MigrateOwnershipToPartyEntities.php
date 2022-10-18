<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\AbstractMigrateToPartyEntities;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;

class MigrateOwnershipToPartyEntities extends AbstractMigrateToPartyEntities
{
    protected $signature = 'format:migrate-ownership-to-parties';
    protected $description = 'Migrates ownership to party entities';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating ownership to parties...');

        $this->migrateTownshipOwnedInstruments();
        $this->output->writeln('township owned instruments migrated');

        $this->migrateTownshipOwnedProviders();
        $this->output->writeln('township owned providers migrated');

        $this->migrateRegionOwnedInstruments();
        $this->output->writeln('region owned instruments migrated');

        $this->migrateRegionOwnedProviders();
        $this->output->writeln('region owned providers migrated');

        $this->migrateEnvironmentFeaturedTownship();
        $this->output->writeln('township featuring environments migrated');

        $this->migrateEnvironmentFeaturedRegion();
        $this->output->writeln('region featuring environments migrated');

        $this->getOutput()->writeln('migrating ownership to parties finished!');
        return 0;
    }

    public function migrateTownshipOwnedInstruments()
    {
        $instruments = Instrument::query()
            ->withTrashed()
            ->whereHasMorph('owner', ['township', 'App\Models\Township', Township::class])
            ->get();
        $this->migrateTownshipOwned($instruments);
    }

    public function migrateTownshipOwnedProviders()
    {
        $providers = Provider::query()
            ->withTrashed()
            ->whereHasMorph('owner', ['township', 'App\Models\Township', Township::class])
            ->get();
        $this->migrateTownshipOwned($providers);
    }

    public function migrateTownshipOwned(Collection $collection)
    {
        $collection->each(function (Model $ownedEntity) {
            $township = $ownedEntity->owner;
            $localParty = $this->findOrCreateLocalParty($township);
            $ownedEntity->owner()->associate($localParty);
            $ownedEntity->organisation()->associate($localParty->organisation);
            $ownedEntity->saveQuietly();
        });
    }

    public function migrateRegionOwnedInstruments()
    {
        $instruments = Instrument::query()
            ->withTrashed()
            ->whereHasMorph('owner', ['region', 'App\Models\Region', Region::class])
            ->get();
        $this->migrateRegionOwned($instruments);
    }

    public function migrateRegionOwnedProviders()
    {
        $providers = Provider::query()
            ->withTrashed()
            ->whereHasMorph('owner', ['region', 'App\Models\Region', Region::class])
            ->get();
        $this->migrateRegionOwned($providers);
    }

    public function migrateRegionOwned(Collection $collection)
    {
        $collection->each(function (Model $ownedEntity) {
            $region = $ownedEntity->owner;
            $regionalParty = $this->findOrCreateRegionalParty($region);
            $ownedEntity->owner()->associate($regionalParty);
            $ownedEntity->organisation()->associate($regionalParty->organisation);
            $ownedEntity->save();
        });
    }

    public function migrateEnvironmentFeaturedTownship()
    {
        $environments = Environment::query()
            ->withTrashed()
            ->whereHasMorph('featuredAssociation', ['township', 'App\Models\Township', Township::class])
            ->get();
        $this->migrateFeaturedTownship($environments);
    }

    public function migrateFeaturedTownship(Collection $collection)
    {
        $collection->each(function (Environment $environment) {
            $township = $environment->featuredAssociation;
            $localParty = $this->findOrCreateLocalParty($township);
            $environment->featuredAssociation()->associate($localParty);
            $environment->featuredOrganisations()->syncWithoutDetaching($localParty->organisation);
            $environment->save();
        });
    }

    public function migrateEnvironmentFeaturedRegion()
    {
        $environments = Environment::query()
            ->withTrashed()
            ->whereHasMorph('featuredAssociation', ['region', 'App\Models\Region', Region::class])
            ->get();
        $this->migrateFeaturedRegion($environments);
    }

    public function migrateFeaturedRegion(Collection $collection)
    {
        $collection->each(function (Environment $environment) {
            $region = $environment->featuredAssociation;
            $regionalParty = $this->findOrCreateRegionalParty($region);
            $environment->featuredAssociation()->associate($regionalParty);
            $environment->featuredOrganisations()->syncWithoutDetaching($regionalParty->organisation);
            $environment->save();
        });
    }
}
