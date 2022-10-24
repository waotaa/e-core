<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\NationalParty;

class NationalPartyPolicy extends BaseOrganisationPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('nationalParty.viewAny');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param NationalParty $nationalParty
     * @return bool
     */
    public function view(IsManagerInterface $user, NationalParty $nationalParty)
    {
        if($nationalParty->hasMember($user)){
            return true;
        }
        return $user->managerCan('nationalParty.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('nationalParty.create');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param NationalParty $nationalParty
     * @return bool
     */
    public function update(IsManagerInterface $user, NationalParty $nationalParty)
    {
        if($nationalParty->hasMember($user)
            && $user->managerCan('organisation.update')) {
            return true;
        }
        return $user->managerCan('nationalParty.update');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param NationalParty $nationalParty
     * @return bool
     */
    public function delete(IsManagerInterface $user, NationalParty $nationalParty)
    {
        if($nationalParty->hasMember($user)
            && $user->managerCan('association.delete')) {
            return true;
        }
        return $user->managerCan('nationalParty.delete');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param NationalParty $nationalParty
     * @return bool
     */
    public function restore(IsManagerInterface $user, NationalParty $nationalParty)
    {
        if($nationalParty->hasMember($user)
            && $user->managerCan('organisation.restore')) {
            return true;
        }
        return $user->managerCan('nationalParty.restore');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param NationalParty $nationalParty
     * @return bool
     */
    public function forceDelete(IsManagerInterface $user, NationalParty $nationalParty)
    {
        if($nationalParty->hasMember($user)
            && $user->managerCan('organisation.forceDelete')) {
            return true;
        }
        return $user->managerCan('nationalParty.forceDelete');
    }
}
