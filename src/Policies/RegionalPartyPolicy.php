<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\RegionalParty;

class RegionalPartyPolicy extends BaseOrganisationPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('regionalParty.viewAny');
    }

    public function view(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)){
            return true;
        }
        return $user->managerCan('regionalParty.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('regionalParty.create');
    }

    public function update(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)
            && $user->managerCan('organisation.update')) {
            return true;
        }
        return $user->managerCan('regionalParty.update');
    }

    public function delete(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)
            && $user->managerCan('organisation.delete')) {
            return true;
        }
        return $user->managerCan('regionalParty.delete');
    }

    public function restore(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)
            && $user->managerCan('organisation.restore')) {
            return true;
        }
        return $user->managerCan('regionalParty.restore');
    }

    public function forceDelete(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)
            && $user->managerCan('organisation.forceDelete')) {
            return true;
        }
        return $user->managerCan('regionalParty.forceDelete');
    }
}
