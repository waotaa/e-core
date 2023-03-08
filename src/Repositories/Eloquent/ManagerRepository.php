<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Role;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;

class ManagerRepository extends BaseRepository implements ManagerRepositoryInterface
{
    public string $model = Manager::class;

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

    public function attachRole(Manager $manager, Role $role): Manager
    {
        Gate::authorize('attachRole', [$manager, $role]);

        $manager->assignRole($role);
//        $manager->forgetCachedPermissions();
        return $manager;
    }

    public function detachRole(Manager $manager, Role $role): Manager
    {
        Gate::authorize('detachRole', [$manager, $role]);

        $manager->removeRole($role);
//        $manager->forgetCachedPermissions();
        return $manager;
    }
}
