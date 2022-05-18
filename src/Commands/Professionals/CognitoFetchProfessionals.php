<?php

namespace Vng\EvaCore\Commands\Professionals;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\Cognito\CognitoService;

class CognitoFetchProfessionals extends Command
{
    protected $signature = 'professionals:fetch';
    protected $description = 'Find new users in the AWS user pool and save them';

    public function handle(): int
    {
        CognitoService::fetchNewCognitoUsers();
        return 0;
    }
}
