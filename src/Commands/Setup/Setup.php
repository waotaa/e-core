<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;

class Setup extends Command
{
    protected $signature = 'eva-core:setup {--n|no-interaction} {--l|lean}';
    protected $description = 'Setup the core';

    public function handle(): int
    {
        $this->info("\n[ Setting up eva core ]\n");

        $this->call('key:generate');

        $this->setupDatabase();
        if (!$this->option('lean')) {
            $this->setupUtilities();
        }

        $this->info("\n[ Setting up eva core ] - finished!\n");
        return 0;
    }

    private function publishPackage()
    {
        $this->call('vendor:publish', [
            '--provider' => 'Vng\EvaCore\Providers\EvaServiceProvider',
            '--force' => true,
        ]);
    }

    private function setupDatabase()
    {
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call('setup:seed-characteristics');
    }

    private function setupUtilities()
    {
        $this->call('eva:setup-geo');
        $this->call('setup:init-environment');
        $this->call('setup:create-test-instrument');
        $this->call('professionals:setup', ['--no-interaction' => $this->option('no-interaction')]);
    }
}
