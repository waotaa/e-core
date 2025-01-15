<?php

namespace Vng\EvaCore\Commands\Data;

use Illuminate\Console\Command;

class EnsureIntegrity extends Command
{
    protected $signature = 'data:ensure-integrity {--fix}';
    protected $description = 'Runs all data integrity checks';

    public function handle(): int
    {
        $this->getOutput()->writeln('starting ensure integrity...');

        $this->call(CheckLostProfessionals::class, [
            '--fix' => $this->option('fix')
        ]);

        $this->call(CheckOrphanedOrganisations::class, [
            '--fix' => $this->option('fix')
        ]);

        $this->call(CheckSoftDeletedOrganisations::class, [
            '--fix' => $this->option('fix')
        ]);

        $this->getOutput()->writeln('ensure integrity finished!');
        return 0;
    }
}
