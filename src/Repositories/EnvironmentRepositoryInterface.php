<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\EnvironmentCreateRequest;
use Vng\EvaCore\Http\Requests\EnvironmentDetailsUpdateRequest;
use Vng\EvaCore\Http\Requests\EnvironmentUpdateRequest;
use Vng\EvaCore\Models\Environment;

interface EnvironmentRepositoryInterface extends OwnedEntityRepositoryInterface, SoftDeletableRepositoryInterface
{
    public function create(EnvironmentCreateRequest $request): Environment;
    public function update(Environment $environment, EnvironmentUpdateRequest $request): Environment;
    public function updateDetails(Environment $environment, EnvironmentDetailsUpdateRequest $request): Environment;

    public function attachFeaturedOrganisations(Environment $environment, string|array $organisationIds): Environment;
    public function detachFeaturedOrganisations(Environment $environment, string|array $organisationIds): Environment;
}
