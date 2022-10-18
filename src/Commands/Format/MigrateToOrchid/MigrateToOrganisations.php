<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid;

use Illuminate\Console\Command;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations\EnsureOrganisations;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations\MigrateOwnershipToPartyEntities;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations\MigrateToMultipleFeaturedOrganisations;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations\SetOrganisationIdOnOwnedEntities;

class MigrateToOrganisations extends Command
{
    protected $signature = 'format:migrate-organisations';
    protected $description = 'Migrates to organisations';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating to organisations...');

        // create an organisation entity for every owner/organisation entity
        // (local party, regional party, national party, and partnership)
        $this->output->writeln('ensuring organisations');
        $this->call(EnsureOrganisations::class);

        // Region owners to Regional Parties
        // Local owners to Local Parties
        $this->output->writeln('migrate ownership to organisations');
        $this->call(MigrateOwnershipToPartyEntities::class);

        // set organisation_id on owned entities
        $this->call(SetOrganisationIdOnOwnedEntities::class);

        // migrate environment's featured organisations
        $this->call(MigrateToMultipleFeaturedOrganisations::class);

        // all members of organisation entities need a manager entity
//        $this->call(MigrateToManagers::class);

        $this->getOutput()->writeln('migrating to organisations finished!');
        return 0;
    }


}
