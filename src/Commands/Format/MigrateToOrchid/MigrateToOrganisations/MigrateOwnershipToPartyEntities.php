<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\AbstractMigrateToPartyEntities;
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

        $this->getOutput()->writeln('migrating ownership to parties finished!');
        return 0;
    }

    public function migrateTownshipOwnedInstruments()
    {
        $instruments = Instrument::query()->withTrashed()->whereHasMorph('owner', ['township', Township::class])->get();
        $this->migrateTownshipOwned($instruments);
    }

    public function migrateTownshipOwnedProviders()
    {
        $providers = Provider::query()->withTrashed()->whereHasMorph('owner', ['township', Township::class])->get();
        $this->migrateTownshipOwned($providers);
    }

    public function migrateTownshipOwned(Collection $collection)
    {
        $collection->each(function (Model $ownedEntity) {
            $township = $ownedEntity->owner;
            $localParty = $this->findOrCreateLocalParty($township);
            $ownedEntity->owner()->associate($localParty);
            $ownedEntity->organisation()->associate($localParty->organisation);
            $ownedEntity->save();
        });
    }

    public function migrateRegionOwnedInstruments()
    {
        $instruments = Instrument::query()->withTrashed()->whereHasMorph('owner', ['region', Region::class])->get();
        $this->migrateRegionOwned($instruments);
    }

    public function migrateRegionOwnedProviders()
    {
        $providers = Provider::query()->withTrashed()->whereHasMorph('owner', ['region', Region::class])->get();
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
}
