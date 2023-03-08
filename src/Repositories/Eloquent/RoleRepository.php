<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Role;
use Vng\EvaCore\Repositories\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public string $model = Role::class;

    public function attachManager(Role $role, Manager $manager): Role
    {
        $manager->assignRole($role);
        return $role;
    }

    public function detachManager(Role $role, Manager $manager): Role
    {
        $manager->removeRole($role);
        return $role;
    }
}
