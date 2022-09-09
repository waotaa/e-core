<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Role;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;

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

    public function attachOrganisation(Organisation $organisation, Manager $manager): Manager
    {
        $manager->organisations()->syncWithoutDetaching($organisation->id);
        return $manager;
    }

    public function detachOrganisation(Manager $manager, Organisation $organisation): Manager
    {
        $manager->organisations()->detach($organisation->id);
        return $manager;
    }

    public function attachRole(Manager $manager, Role $role): Manager
    {
        $manager->assignRole($role);
        return $manager;
    }

    public function detachRole(Manager $manager, Role $role): Manager
    {
        $manager->removeRole($role);
        return $manager;
    }
}
