<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Interfaces\IsMemberInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\TargetGroup;

class TargetGroupPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * @param IsManagerInterface&IsMemberInterface $user
     * @param TargetGroup $targetGroup
     * @return bool
     */
    private function userOwnsTargetGroup (IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        $isCustom = $targetGroup->custom;
        if ($isCustom) {
            $owningInstrument = $targetGroup->owningInstrument;
            if (!$owningInstrument) {
                return true;
            }
            return $user->managesInstrument($owningInstrument);
        }
        return false;
    }

    /**
     * @param IsManagerInterface&IsMemberInterface&IsOwnerInterface $user
     * @return Collection|null
     */
    private function getOwnedTargetGroups(IsManagerInterface $user): ?Collection
    {
        $isAssociated = $user->hasAnyAssociation();
        if (!$isAssociated) {
            return null;
        }

//        /** @var Collection $userInstruments */
//        $userInstruments = $user->association->ownedInstruments;

        $userInstruments = $user->getAssociationsOwnedInstruments();
        $customTargetGroups = TargetGroup::query()->where('custom', true)->get();
        $ownedTargetGroups = $customTargetGroups->filter(function (TargetGroup $targetGroup) use ($userInstruments) {
            if (is_null($targetGroup->owningInstrument)) {
                return true;
            }
            return $userInstruments->contains('id', $targetGroup->owningInstrument->id);
        });

        return $ownedTargetGroups;
    }

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('targetGroup.viewAny');
    }

    public function view(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        return $user->managerCan('targetGroup.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        if ($user->managerCan('targetGroup.create')) {
            return true;
        }
        if ($user->managerCan('targetGroup.custom.create')) {
            $ownedTargetGroups = $this->getOwnedTargetGroups($user);
            return $ownedTargetGroups && $ownedTargetGroups->count() < 8;
        }
        return false;
    }

    public function update(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($this->userOwnsTargetGroup($user, $targetGroup) && $user->managerCan('targetGroup.custom.update')) {
            return true;
        }
        return $user->managerCan('targetGroup.update');
    }

    public function delete(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($this->userOwnsTargetGroup($user, $targetGroup) && $user->managerCan('targetGroup.custom.delete')) {
            return true;
        }
        return $user->managerCan('targetGroup.delete');
    }

    public function restore(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($this->userOwnsTargetGroup($user, $targetGroup) && $user->managerCan('targetGroup.custom.restore')) {
            return true;
        }
        return $user->managerCan('targetGroup.restore');
    }

    public function forceDelete(IsManagerInterface $user, TargetGroup $targetGroup): bool
    {
        if ($this->userOwnsTargetGroup($user, $targetGroup) && $user->managerCan('targetGroup.custom.forceDelete')) {
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
