<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers;
use Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToOrganisations;

class MigrateToOrchid extends Command
{
    protected $signature = 'format:migrate-orchid';
    protected $description = 'Runs some migration script needed to migrate to orchid from nova';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating to orchid...');

        $this->output->writeln('migrating organisations');
        $this->call(MigrateToOrganisations::class);

        $this->output->writeln('migrating managers');
        $this->call(MigrateToManagers::class);

        $this->output->writeln('applying morph map');
        $this->call(ApplyMorphMap::class);

        $this->getOutput()->writeln('migrating to orchid finished!');
        return 0;
    }


}
