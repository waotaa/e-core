<?php

namespace Vng\EvaCore\Policies;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('address.viewAny');
    }

    public function viewAll(IsManagerInterface $user)
    {
        return $user->managerCan('address.viewAll');
    }

    public function view(IsManagerInterface $user, Address $address)
    {
        if ($address->hasOwner()
            && $user->managerCan('address.organisation.view')
            && $address->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $this->viewAll($user);
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('address.organisation.create')
            || $user->managerCan('address.create');
    }

    public function update(IsManagerInterface $user, Address $address)
    {
        if ($address->hasOwner()
            && $user->managerCan('address.organisation.update')
            && $address->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('address.update');
    }

    public function delete(IsManagerInterface $user, Address $address)
    {
        if ($address->hasOwner()
            && $user->managerCan('address.organisation.delete')
            && $address->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('address.delete');
    }

    // attach instrument
    // detach instrument
}
