<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Services\Cognito\CognitoService;

class ProfessionalObserver
{
    public function saving(Professional $professional): void
    {
        $professional->username = $professional->email;
    }

    public function created(Professional $professional): void
    {
        CognitoService::createProfessional($professional);
    }

    public function deleted(Professional $professional): void
    {
        CognitoService::deleteProfessional($professional);
    }
}
