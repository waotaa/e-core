<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Interfaces\IsMemberInterface;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Models\Instrument;

class ImplementationPolicy extends BasePolicy
{
    use HandlesAuthorization;

    private function userOwnsImplementation (IsMemberInterface $user, Implementation $implementation): bool
    {
        $isCustom = $implementation->custom;
        if ($isCustom) {
            $owningInstrument = $implementation->owningInstrument;
            if (!$owningInstrument) {
                return true;
            }
            return $user->managesInstrument($owningInstrument);
        }
        return false;
    }

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('implementation.viewAny');
    }

    /**
     * @param IsManagerInterface&IsMemberInterface $user
     * @param Implementation $implementation
     * @return bool
     */
    public function view(IsManagerInterface $user, Implementation $implementation): bool
    {
        return $user->managerCan('implementation.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('implementation.create') || $user->managerCan('implementation.custom.create');
    }

    /**
     * @param IsManagerInterface&IsMemberInterface $user
     * @param Implementation $implementation
     * @return bool
     */
    public function update(IsManagerInterface $user, Implementation $implementation): bool
    {
        if ($this->userOwnsImplementation($user, $implementation) && $user->managerCan('implementation.custom.update')) {
            return true;
        }
        return $user->managerCan('implementation.update');
    }

    /**
     * @param IsManagerInterface&IsMemberInterface $user
     * @param Implementation $implementation
     * @return bool
     */
    public function delete(IsManagerInterface $user, Implementation $implementation): bool
    {
        if ($this->userOwnsImplementation($user, $implementation) && $user->managerCan('implementation.custom.delete')) {
            return true;
        }
        return $user->managerCan('implementation.delete');
    }

    /**
     * @param IsManagerInterface&IsMemberInterface $user
     * @param Implementation $implementation
     * @return bool
     */
    public function restore(IsManagerInterface $user, Implementation $implementation): bool
    {
        if ($this->userOwnsImplementation($user, $implementation) && $user->managerCan('implementation.custom.restore')) {
            return true;
        }
        return $user->managerCan('implementation.restore');
    }

    /**
     * @param IsManagerInterface&IsMemberInterface $user
     * @param Implementation $implementation
     * @return bool
     */
    public function forceDelete(IsManagerInterface $user, Implementation $implementation): bool
    {
        if ($this->userOwnsImplementation($user, $implementation) && $user->managerCan('implementation.custom.forceDelete')) {
            return true;
        }
        return $user->managerCan('implementation.forceDelete');
    }
}
