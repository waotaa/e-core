<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\HasOwnerInterface;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegionalParty;
use Vng\EvaCore\Models\Township;

class SetOrganisationIdOnOwnedEntities extends Command
{
    protected $signature = 'format:set-organisation-on-owned-entities';
    protected $description = 'Set organisation on owned entities';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating ownership to parties...');

        $this->migrateLocalPartyOwned();
        $this->output->writeln('local party owned migrated');

        $this->migrateRegionalPartyOwned();
        $this->output->writeln('regional party owned migrated');

        $this->migrateNationalPartyOwned();
        $this->output->writeln('national party owned migrated');

        $this->migratePartnershipOwned();
        $this->output->writeln('partnership owned migrated');

        $this->getOutput()->writeln('migrating ownership to parties finished!');
        return 0;
    }

    public function migrateLocalPartyOwned()
    {
        $instruments = Instrument::query()->withTrashed()->whereHasMorph('owner', LocalParty::class)->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()->whereHasMorph('owner', LocalParty::class)->get();
        $this->migrateOwnedEntities($providers);
    }

    public function migrateRegionalPartyOwned()
    {
        $instruments = Instrument::query()->withTrashed()->whereHasMorph('owner', RegionalParty::class)->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()->whereHasMorph('owner', RegionalParty::class)->get();
        $this->migrateOwnedEntities($providers);
    }

    public function migrateNationalPartyOwned()
    {
        $instruments = Instrument::query()->withTrashed()->whereHasMorph('owner', NationalParty::class)->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()->whereHasMorph('owner', NationalParty::class)->get();
        $this->migrateOwnedEntities($providers);
    }

    public function migratePartnershipOwned()
    {
        $instruments = Instrument::query()->withTrashed()->whereHasMorph('owner', Partnership::class)->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()->whereHasMorph('owner', Partnership::class)->get();
        $this->migrateOwnedEntities($providers);
    }

    private function migrateOwnedEntities(Collection $collection)
    {
        $collection->each(function (Model $ownedEntity) {
            $owner = $ownedEntity->owner;
            $ownedEntity->organisation()->associate($owner->organisation);
            $ownedEntity->save();
        });
    }

}
