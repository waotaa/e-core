<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Role;

interface ManagerRepositoryInterface extends BaseRepositoryInterface
{
    public function createForUser(IsManagerInterface $user): Manager;

    public function attachOrganisation(Organisation $organisation, Manager $manager): Manager;
    public function detachOrganisation(Manager $manager, Organisation $organisation): Manager;

    public function attachRole(Manager $manager, Role $role): Manager;
    public function detachRole(Manager $manager, Role $role): Manager;
}
