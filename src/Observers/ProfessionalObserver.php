<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Services\Cognito\CognitoService;

class ProfessionalObserver
{
    public function creating(Professional $professional): void
    {
        // temporary; will be overwritten on saved
        $professional->username = $professional->email;
    }

    public function saved(Professional $professional): void
    {
        if (CognitoService::hasRequiredConfig()) {
            $cognitoService = CognitoService::make($professional->environment);
            $profModel = $cognitoService->getUser($professional);
            if (is_null($profModel)) {
                $cognitoService->createProfessional($professional);
            }
        }
    }

    public function deleted(Professional $professional): void
    {
        if (CognitoService::hasRequiredConfig()) {
            CognitoService::make($professional->environment)->deleteProfessional($professional);
        }
    }
}
