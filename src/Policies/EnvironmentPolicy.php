<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Environment;

class EnvironmentPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('environment.viewAny');
    }

    public function view(IsManagerInterface $user, Environment $environment)
    {
        return $user->managerCan('environment.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('environment.create');
    }

    public function update(IsManagerInterface $user, Environment $environment)
    {
        return $user->managerCan('environment.update');
    }

    public function delete(IsManagerInterface $user, Environment $environment)
    {
        return $user->managerCan('environment.delete');
    }

    public function restore(IsManagerInterface $user, Environment $environment)
    {
        return $user->managerCan('environment.restore');
    }

    public function forceDelete(IsManagerInterface $user, Environment $environment)
    {
        return $user->managerCan('environment.forceDelete');
    }
}
