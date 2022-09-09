<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid;

use Illuminate\Console\Command;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers\DeductManagerDataFromUser;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers\EnsureManagers;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers\MigrateMembershipToPartyEntities;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers\MigrateMembersToOrganisations;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers\MigrateNovaRoles;

class MigrateToManagers extends Command
{
    protected $signature = 'format:migrate-managers';
    protected $description = 'Migrates to managers';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating to managers...');

        $this->call(EnsureManagers::class);

        $this->call(MigrateMembershipToPartyEntities::class);

        // All user associateables need to be migrated to manager - organisation relations
        // That manager can be linked to the organisation
        $this->call(MigrateMembersToOrganisations::class);

        $this->call(DeductManagerDataFromUser::class);

        $this->call(MigrateNovaRoles::class);

        $this->getOutput()->writeln('migrating to managers finished!');
        return 0;
    }
}
