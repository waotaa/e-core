<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\LocalParty;

class LocalPartyPolicy extends BaseOrganisationPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('viewAny localParty');
    }

    public function view(IsManagerInterface $user, LocalParty $localParty)
    {
        if($localParty->hasMember($user)){
            return true;
        }
        return $user->managerCan('view localParty');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('create localParty');
    }

    public function update(IsManagerInterface $user, LocalParty $localParty)
    {
        if($localParty->hasMember($user)
            && $user->managerCan('update association')) {
            return true;
        }
        return $user->managerCan('update localParty');
    }

    public function delete(IsManagerInterface $user, LocalParty $localParty)
    {
        if($localParty->hasMember($user)
            && $user->managerCan('delete association')) {
            return true;
        }
        return $user->managerCan('delete localParty');
    }

    public function restore(IsManagerInterface $user, LocalParty $localParty)
    {
        if($localParty->hasMember($user)
            && $user->managerCan('restore association')) {
            return true;
        }
        return $user->managerCan('restore localParty');
    }

    public function forceDelete(IsManagerInterface $user, LocalParty $localParty)
    {
        if($localParty->hasMember($user)
            && $user->managerCan('forceDelete association')) {
            return true;
        }
        return $user->managerCan('forceDelete localParty');
    }
}
