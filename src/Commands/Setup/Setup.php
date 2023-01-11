<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;
use Vng\EvaCore\Commands\Operations\SetupGeoData;
use Vng\EvaCore\Commands\Professionals\CognitoSetup;

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

    private function setupDatabase()
    {
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call(SeedCharacteristics::class);
        $this->call(SetupAuthorizationMatrix::class);
    }

    private function setupUtilities()
    {
        $this->call(SetupGeoData::class);

        $this->call(InitializeEnvironment::class);
        $this->call(CreateTestInstrument::class);

        $this->call(CognitoSetup::class, ['--no-interaction' => $this->option('no-interaction')]);
    }
}
