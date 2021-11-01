<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;

class ProfessionalPasswordExpirationCheck extends Command
{
    protected $signature = 'professionals:password-expiration';
    protected $description = 'Reset passwords if expired';

    public function handle(): int
    {
        CognitoService::resetExpiredPasswords();
        return 0;
    }
}
