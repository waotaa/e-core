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

    public function viewAll(IsManagerInterface $user): bool
    {
        return $user->managerCan('environment.viewAll');
    }

    public function view(IsManagerInterface $user, Environment $environment)
    {
        if ($environment->hasOwner()
            && $user->managerCan('environment.organisation.view')
            && $environment->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('environment.view') || $this->viewAll($user);
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('environment.organisation.create')
            || $user->managerCan('environment.create');
    }

    public function update(IsManagerInterface $user, Environment $environment)
    {
        if ($environment->hasOwner()
            && $user->managerCan('environment.organisation.update')
            && $environment->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('environment.update');
    }

    public function delete(IsManagerInterface $user, Environment $environment)
    {
        if ($environment->hasOwner()
            && $user->managerCan('environment.organisation.delete')
            && $environment->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('environment.delete');
    }

    public function restore(IsManagerInterface $user, Environment $environment)
    {
        if ($environment->hasOwner()
            && $user->managerCan('environment.organisation.restore')
            && $environment->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('environment.restore');
    }

    public function forceDelete(IsManagerInterface $user, Environment $environment)
    {
        if ($environment->hasOwner()
            && $user->managerCan('environment.organisation.forceDelete')
            && $environment->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('environment.forceDelete');
    }
}
