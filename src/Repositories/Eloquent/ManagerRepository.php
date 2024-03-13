<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Role;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;
use Vng\EvaCore\Repositories\RoleRepositoryInterface;

class ManagerRepository extends BaseRepository implements ManagerRepositoryInterface
{
    public string $model = Manager::class;

    public function addMultipleOrganisationConditions(Builder $query, Collection $organisations): Builder
    {
        $query->where(function(Builder $query) use ($organisations) {
            $organisations->each(function (Organisation $organisation) use (&$query) {
                $query->orWhere(function(Builder $query) use ($organisation) {
                    return $this->addOrganisationCondition($query, $organisation);
                });
            });
        });
        return $query;
    }

    public function addOrganisationCondition(Builder $query, Organisation $organisation): Builder
    {
        return $query->whereHas('organisations', function (Builder $query) use ($organisation) {
            $query->where('id', $organisation->id);
        });
    }

    /**
     * @param IsManagerInterface&EvaUserInterface&Model $user
     * @return Manager
     */
    public function createForUser(IsManagerInterface $user): Manager
    {
        /** @var Manager $manager */
        $manager = $this->new();
        $manager->fill([
            'givenName' => $user->getGivenName(),
            'surName' => $user->getSurName(),
            'email' => $user->getEmail(),
        ]);
        $manager->save();

        $user->manager()->associate($manager);
        $user->save();
        return $manager;
    }

    public function update(Manager $manager, $attributes): Manager
    {
        $manager->fill([
            'givenName' => $attributes['givenName'],
            'surName' => $attributes['surName'],
            'months_unupdated_limit' => $attributes['months_unupdated_limit'],
        ]);

        $manager->save();
        return $manager;
    }

    public function associateCreatedBy(Manager $manager, Manager $createdByManager)
    {
        $manager->createdBy()->associate($createdByManager);
        $manager->save();
    }

    public function attachOrganisations(Manager $manager, string|array $organisationIds): Manager
    {
        $organisationIds = (array) $organisationIds;

        /** @var OrganisationRepositoryInterface $organisationRepo */
        $organisationRepo = app(OrganisationRepositoryInterface::class);
        $organisations = $organisationRepo->builder()->whereIn('id', $organisationIds)->get();
        $organisations->each(fn (Organisation $org) => Gate::authorize('attachOrganisation', [$manager, $org]));

        $manager->organisations()->syncWithoutDetaching($organisationIds);
        return $manager;
    }

    public function detachOrganisations(Manager $manager, string|array $organisationIds): Manager
    {
        $organisationIds = (array) $organisationIds;

        /** @var OrganisationRepositoryInterface $organisationRepo */
        $organisationRepo = app(OrganisationRepositoryInterface::class);
        $organisations = $organisationRepo->builder()->whereIn('id', $organisationIds)->get();
        $organisations->each(fn (Organisation $org) => Gate::authorize('detachOrganisation', [$manager, $org]));

        $manager->organisations()->detach($organisationIds);
        return $manager;
    }

    public function syncOrganisations(Manager $manager, string|array $organisationIds): Manager
    {
        $organisationIds = (array) $organisationIds;

        // Check if current attached orgs who will be detached may be detached
        $manager->organisations->filter(function (Organisation $org) use ($organisationIds) {
            // Return true if the org's id is NOT in the $organisationIds array
            return !in_array($org->id, $organisationIds);
        })->each(fn (Organisation $org) => Gate::authorize('detachOrganisation', [$manager, $org]));

        /** @var OrganisationRepositoryInterface $organisationRepo */
        $organisationRepo = app(OrganisationRepositoryInterface::class);
        $organisations = $organisationRepo->builder()->whereIn('id', $organisationIds)->get();
        $organisations->each(fn (Organisation $org) => Gate::authorize('attachOrganisation', [$manager, $org]));

        $manager->organisations()->sync($organisationIds);
        return $manager;
    }

    public function attachRole(Manager $manager, Role $role): Manager
    {
        Gate::authorize('attachRole', [$manager, $role]);

        $manager->assignRole($role);
        return $manager;
    }

    public function detachRole(Manager $manager, Role $role): Manager
    {
        Gate::authorize('detachRole', [$manager, $role]);

        $manager->removeRole($role);
        return $manager;
    }

    public function syncRoles(Manager $manager, string|array $roleIds): Manager
    {
        $roleIds = (array) $roleIds;

        // Check if current attached orgs who will be detached may be detached
        $manager->roles->filter(function (Role $role) use ($roleIds) {
            // Return true if the org's id is NOT in the $organisationIds array
            return !in_array($role->id, $roleIds);
        })->each(fn (Role $role) => Gate::authorize('detachRole', [$manager, $role]));

        /** @var RoleRepositoryInterface $roleRepo */
        $roleRepo = app(RoleRepositoryInterface::class);
        $roles = $roleRepo->builder()->whereIn('id', $roleIds)->get();
        $roles->each(fn (Role $role) => Gate::authorize('attachRole', [$manager, $role]));

        $manager->syncRoles($roleIds);

        return $manager;
    }
}
