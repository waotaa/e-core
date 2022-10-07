<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Instrument;

class GroupFormPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('groupForm.viewAny');
    }

    public function view(IsManagerInterface $user, GroupForm $groupForm)
    {
        return $user->managerCan('groupForm.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('create groupForm');
    }

    public function update(IsManagerInterface $user, GroupForm $groupForm)
    {
        return $user->managerCan('update groupForm');
    }

    public function delete(IsManagerInterface $user, GroupForm $groupForm)
    {
        return $user->managerCan('delete groupForm');
    }

    public function restore(IsManagerInterface $user, GroupForm $groupForm)
    {
        return $user->managerCan('restore groupForm');
    }

    public function forceDelete(IsManagerInterface $user, GroupForm $groupForm)
    {
        return $user->managerCan('forceDelete groupForm');
    }


    /**
     * @param IsManagerInterface&Authorizable $user
     * @param GroupForm $groupForm
     * @param Instrument $instrument
     * @return bool
     */
    public function attachInstrument(IsManagerInterface $user, GroupForm $groupForm, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param GroupForm $groupForm
     * @param Instrument $instrument
     * @return bool
     */
    public function detachInstrument(IsManagerInterface $user, GroupForm $groupForm, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }
}
