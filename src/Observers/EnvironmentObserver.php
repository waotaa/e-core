<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\Cognito\CognitoService;

class EnvironmentObserver
{
    public function saving(Environment $environment): void
    {
        CognitoService::make($environment)->ensureSetup();
    }
}
