<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function attachManager(Role $role, Manager $manager): Role;
    public function detachManager(Role $role, Manager $manager): Role;
}
