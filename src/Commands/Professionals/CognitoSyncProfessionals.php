<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;

class CognitoSyncProfessionals extends Command
{
    protected $signature = 'professionals:sync {--d|destructive}';
    protected $description = 'Sync the professionals with the AWS user pool';

    public function handle(): int
    {
        $destructive = $this->option('destructive');
        CognitoService::syncProfessionals($destructive);
        return 0;
    }
}
