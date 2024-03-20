<?php

namespace Vng\EvaCore\Commands\Professionals;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;

class AbstractCognitoCommand extends Command
{
    public function hasValidConfig(): bool
    {
        if (!CognitoService::hasRequiredConfig()) {
            $message = 'AWS Config missing: Could not sync professionals';
            Log::warning($message);
            $this->warn($message);
            return false;
        }
        return true;
    }
}
