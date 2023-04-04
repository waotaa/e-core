<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Http\Requests\OrganisationCreateRequest;
use Vng\EvaCore\Http\Requests\OrganisationUpdateRequest;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;

interface OrganisationRepositoryInterface
{
    public function addManagerIsMemberCondition(Builder $query, Manager $manager): Builder;

    public function create(OrganisationCreateRequest $request): Organisation;
    public function update(Organisation $organisation, OrganisationUpdateRequest $request): Organisation;

    public function associateOrganisationable(Model $organisationEntity): ?Organisation;

    public function attachManagers(Organisation $organisation, string|array $managerIds): Organisation;
    public function detachManagers(Organisation $organisation, string|array $managerIds): Organisation;

    public function attachFeaturingEnvironments(Organisation $organisation, string|array $environmentIds): Organisation;
    public function detachFeaturingEnvironments(Organisation $organisation, string|array $environmentIds): Organisation;

    public function attachContacts(Organisation $organisation, string|array $contactIds): Organisation;
    public function detachContacts(Organisation $organisation, string|array $contactIds): Organisation;
}
