<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\TargetGroup;

class TargetGroupPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('targetGroup.viewAny');
    }

    public function viewAll(IsManagerInterface $user)
    {
        return $user->managerCan('targetGroup.viewAll');
    }

    public function view(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($targetGroup->hasOwner()
            && $user->managerCan('targetGroup.organisation.view')
            && $targetGroup->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('targetGroup.view') || $this->viewAll($user);

    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('targetGroup.organisation.create')
            || $user->managerCan('targetGroup.create');
    }

    public function update(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($targetGroup->hasOwner()
            && $user->managerCan('targetGroup.organisation.update')
            && $targetGroup->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('targetGroup.update');
    }

    public function delete(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($targetGroup->hasOwner()
            && $user->managerCan('targetGroup.organisation.delete')
            && $targetGroup->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('targetGroup.delete');
    }

    public function restore(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($targetGroup->hasOwner()
            && $user->managerCan('targetGroup.organisation.restore')
            && $targetGroup->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('targetGroup.restore');
    }

    public function forceDelete(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($targetGroup->hasOwner()
            && $user->managerCan('targetGroup.organisation.forceDelete')
            && $targetGroup->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('targetGroup.forceDelete');
    }


    /**
     * @param IsManagerInterface&Authorizable $user
     * @param TargetGroup $targetGroup
     * @param Instrument $instrument
     * @return bool
     */
    public function attachInstrument(IsManagerInterface $user, TargetGroup $targetGroup, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param TargetGroup $targetGroup
     * @param Instrument $instrument
     * @return bool
     */
    public function detachInstrument(IsManagerInterface $user, TargetGroup $targetGroup, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }
}
