<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Vng\EvaCore\Commands\Format\CleanupSyncAttempts;
use Vng\EvaCore\Commands\Professionals\CognitoSetup;

class Update extends Command
{
    protected $signature = 'eva-core:update';
    protected $description = 'Post deploy update script';

    public function handle(): int
    {
        if (App::environment('local')) {
            $this->call('optimize');
        } else {
            $this->call('config:cache');
//            Optimize wants to run this command, but fails for now
//            $this->call('route:cache');
            $this->call('route:clear');
            $this->call('view:cache');
        }

        $this->call('migrate', ['--force' => true]);
        $this->call(CleanupSyncAttempts::class);
        $this->call(SeedCharacteristics::class);
        $this->call(SetupAuthorizationMatrix::class);
        $this->call(CognitoSetup::class, ['--no-interaction' => true]);
        return 0;
    }
}
