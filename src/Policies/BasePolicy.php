<?php

namespace Vng\EvaCore\Policies;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;

abstract class BasePolicy
{
    public function can(IsManagerInterface $user, $permission)
    {
        return $user->managerCan($permission);
    }

    public function before(IsManagerInterface $user)
    {
        return $this->getManager($user)->isSuperAdmin();
    }

    protected function getManager(IsManagerInterface $user): Manager
    {
        /** @var Manager $manager */
        $manager = $user->manager;
        if (is_null($manager)) {
            throw new \Exception('No manager found on user');
        }
        return $manager;
    }
}