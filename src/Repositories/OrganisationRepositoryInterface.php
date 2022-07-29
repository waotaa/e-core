<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\OrganisationCreateRequest;
use Vng\EvaCore\Http\Requests\OrganisationUpdateRequest;
use Vng\EvaCore\Models\Organisation;

interface OrganisationRepositoryInterface
{
    public function create(OrganisationCreateRequest $request): Organisation;
    public function update(Organisation $organisation, OrganisationUpdateRequest $request): Organisation;
}
