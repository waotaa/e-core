<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;

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
        $this->call(MigrateOwnershipToPartyEntities::class);

        // set organisation_id on owned entities
        $this->call(SetOrganisationIdOnOwnedEntities::class);

        // all members of organisation entities need a manager entity
        $this->call(MigrateToManagers::class);

        $this->getOutput()->writeln('migrating to organisations finished!');
        return 0;
    }


}
