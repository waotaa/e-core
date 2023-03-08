<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Provider;

class ProviderPolicy extends BasePolicy
{

    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('provider.viewAny');
    }

    public function viewAll(IsManagerInterface $user)
    {
        return $user->managerCan('provider.viewAll');
    }

    public function view(IsManagerInterface $user, Provider $provider): bool
    {
//        return $user->managerCan('provider.viewAny');

        if ($provider->hasOwner()
            && $user->managerCan('provider.organisation.view')
            && $provider->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('provider.view') || $this->viewAll($user);
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('provider.organisation.create')
            || $user->managerCan('provider.create');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Provider $provider
     * @return bool
     */
    public function update(IsManagerInterface $user, Provider $provider): bool
    {
        if ($provider->hasOwner()
            && $user->managerCan('provider.organisation.update')
            && $provider->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('provider.update');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Provider $provider
     * @return bool
     */
    public function delete(IsManagerInterface $user, Provider $provider): bool
    {
        if ($provider->hasOwner()
            && $user->managerCan('provider.organisation.delete')
            && $provider->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('provider.delete');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Provider $provider
     * @return bool
     */
    public function restore(IsManagerInterface $user, Provider $provider): bool
    {
        if ($provider->hasOwner()
            && $user->managerCan('provider.organisation.restore')
            && $provider->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('provider.restore');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Provider $provider
     * @return bool
     */
    public function forceDelete(IsManagerInterface $user, Provider $provider): bool
    {
        if ($provider->hasOwner()
            && $user->managerCan('provider.organisation.forceDelete')
            && $provider->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('provider.forceDelete');
    }


//    All things you can do when you can edit
    public function attachAnyContact(IsManagerInterface $user, Provider $provider): bool
    {
        return $this->update($user, $provider);
    }
    public function attachContact(IsManagerInterface $user, Provider $provider): bool
    {
        return $this->update($user, $provider);
    }
    public function detachContact(IsManagerInterface $user, Provider $provider): bool
    {
        return $this->update($user, $provider);
    }

    public function addInstrument(IsManagerInterface $user, Provider $provider): bool
    {
        return $this->update($user, $provider);
    }
}
