<?php

namespace Vng\EvaCore\Commands\Operations;

use Illuminate\Console\Command;

class AddRsdDeLiemers extends Command
{
    protected $signature = 'eva:add-rsd-de-liemers';
    protected $description = 'Add RSD de liemers';

    public function handle(): int
    {
        // Setup test environment
        $this->call('db:seed', ['--class' => 'Database\Seeders\Environments\RsdDeLiemersSeeder', '--force' => true]);
        $this->call('elastic:sync-environments', [
            '--fresh' => true,
        ]);
        return 0;
    }
}
