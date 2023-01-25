<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Repositories\NationalPartyRepositoryInterface;
use Vng\EvaCore\Services\Cognito\CognitoService;

class EnvironmentObserver
{
    public function __construct(
        protected NationalPartyRepositoryInterface $nationalPartyRepository
    )
    {}

    public function created(Environment $environment): void
    {
        $nationalParties = $this->nationalPartyRepository->all();
        $environment->featuredOrganisations()->syncWithoutDetaching($nationalParties->pluck('organisation.id'));
        $environment->save();
    }

    public function saving(Environment $environment): void
    {
        CognitoService::make($environment)->ensureSetup();
    }
}
