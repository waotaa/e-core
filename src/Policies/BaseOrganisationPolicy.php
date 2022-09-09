<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\HasMembersInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;

abstract class BaseOrganisationPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * @param Model&IsManagerInterface $user
     * @param HasMembersInterface $organisation
     * @return bool
     */
    public function addUser(IsManagerInterface $user, HasMembersInterface $organisation): bool
    {
        if ($user->managerCan('manager.organisation.create')
            && $organisation->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('manager.create');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param HasMembersInterface $organisation
     * @return bool
     */
    public function addInstrument(IsManagerInterface $user, HasMembersInterface $organisation): bool
    {
        if ($user->managerCan('instrument.organisation.create')
            && $organisation->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('instrument.create');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param HasMembersInterface $organisation
     * @return bool
     */
    public function addProvider(IsManagerInterface $user, HasMembersInterface $organisation): bool
    {
        if ($user->managerCan('provider.organisation.create')
            && $organisation->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('provider.create');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param HasMembersInterface $organisation
     * @return bool
     */
    public function addContact(IsManagerInterface $user, HasMembersInterface $organisation): bool
    {
        if ($user->managerCan('contact.organisation.create')
            && $organisation->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('contact.create');
    }
}
