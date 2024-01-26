<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Repositories\NationalPartyRepositoryInterface;
use Vng\EvaCore\Services\Cognito\CognitoService;
use Vng\EvaCore\Services\ElasticSearch\KibanaService;

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
        if (CognitoService::hasRequiredConfig()) {
            CognitoService::make($environment)->ensureSetup();
        }
    }

    public function saved(Environment $environment): void
    {
        // ensure kibana setup
        KibanaService::make($environment)->ensureKibanaSetup();
    }
}
