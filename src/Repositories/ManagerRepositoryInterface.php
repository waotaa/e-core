<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Role;

interface ManagerRepositoryInterface extends BaseRepositoryInterface
{
    public function addMultipleOrganisationConditions(Builder $query, Collection $organisations): Builder;
    public function addOrganisationCondition(Builder $query, Organisation $organisation): Builder;

    public function createForUser(IsManagerInterface $user): Manager;
    public function update(Manager $manager, array $attributes): Manager;
    public function associateCreatedBy(Manager $manager, Manager $createdByManager);

    public function attachOrganisations(Manager $manager, string|array $organisationIds): Manager;
    public function detachOrganisations(Manager $manager, string|array $organisationIds): Manager;

    public function attachRole(Manager $manager, Role $role): Manager;
    public function detachRole(Manager $manager, Role $role): Manager;
}
