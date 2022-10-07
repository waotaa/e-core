<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;

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
        $instruments = Instrument::query()->withTrashed()
            ->where('owner_type', 'App\Models\LocalParty')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\LocalParty')
            ->orWhere('owner_type', 'local-party')
            ->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()
            ->where('owner_type', 'App\Models\LocalParty')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\LocalParty')
            ->orWhere('owner_type', 'local-party')
            ->get();
        $this->migrateOwnedEntities($providers);
    }

    public function migrateRegionalPartyOwned()
    {
        $instruments = Instrument::query()->withTrashed()
            ->where('owner_type', 'App\Models\RegionalParty')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\RegionalParty')
            ->orWhere('owner_type', 'regional-party')
            ->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()
            ->where('owner_type', 'App\Models\RegionalParty')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\RegionalParty')
            ->orWhere('owner_type', 'regional-party')
            ->get();
        $this->migrateOwnedEntities($providers);
    }

    public function migrateNationalPartyOwned()
    {
        $instruments = Instrument::query()->withTrashed()
            ->where('owner_type', 'App\Models\NationalParty')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\NationalParty')
            ->orWhere('owner_type', 'national-party')
            ->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()
            ->where('owner_type', 'App\Models\NationalParty')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\NationalParty')
            ->orWhere('owner_type', 'national-party')
            ->get();
        $this->migrateOwnedEntities($providers);
    }

    public function migratePartnershipOwned()
    {
        $instruments = Instrument::query()->withTrashed()
            ->where('owner_type', 'App\Models\Partnership')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\Partnership')
            ->orWhere('owner_type', 'partnership')
            ->get();
        $this->migrateOwnedEntities($instruments);

        $providers = Provider::query()->withTrashed()
            ->where('owner_type', 'App\Models\Partnership')
            ->orWhere('owner_type', 'Vng\EvaCore\Models\Partnership')
            ->orWhere('owner_type', 'partnership')
            ->get();
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
