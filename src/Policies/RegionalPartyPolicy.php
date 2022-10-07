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
            && $user->managerCan('update association')) {
            return true;
        }
        return $user->managerCan('regionalParty.update');
    }

    public function delete(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)
            && $user->managerCan('delete association')) {
            return true;
        }
        return $user->managerCan('regionalParty.delete');
    }

    public function restore(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)
            && $user->managerCan('restore association')) {
            return true;
        }
        return $user->managerCan('regionalParty.restore');
    }

    public function forceDelete(IsManagerInterface $user, RegionalParty $regionalParty): bool
    {
        if($regionalParty->hasMember($user)
            && $user->managerCan('forceDelete association')) {
            return true;
        }
        return $user->managerCan('regionalParty.forceDelete');
    }
}
