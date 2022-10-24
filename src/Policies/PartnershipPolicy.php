<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Township;

class PartnershipPolicy extends BaseOrganisationPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('partnership.viewAny');
    }

    public function view(IsManagerInterface $user, Partnership $partnership): bool
    {
        if($partnership->hasMember($user)){
            return true;
        }
        return $user->managerCan('partnership.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('partnership.create');
    }

    public function update(IsManagerInterface $user, Partnership $partnership): bool
    {
        if($partnership->hasMember($user)
            && $user->managerCan('organisation.update')) {
            return true;
        }
        return $user->managerCan('partnership.update');
    }

    public function delete(IsManagerInterface $user, Partnership $partnership): bool
    {
        if($partnership->hasMember($user)
            && $user->managerCan('organisation.delete')) {
            return true;
        }
        return $user->managerCan('partnership.delete');
    }

    public function restore(IsManagerInterface $user, Partnership $partnership): bool
    {
        if($partnership->hasMember($user)
            && $user->managerCan('organisation.restore')) {
            return true;
        }
        return $user->managerCan('partnership.restore');
    }

    public function forceDelete(IsManagerInterface $user, Partnership $partnership): bool
    {
        if($partnership->hasMember($user)
            && $user->managerCan('organisation.forceDelete')) {
            return true;
        }
        return $user->managerCan('partnership.forceDelete');
    }

    public function attachAnyTownship(IsManagerInterface $user, Partnership $partnership): bool
    {
        return $user->managerCan('partnership.update');
    }
    public function attachTownship(IsManagerInterface $user, Partnership $partnership, Township $township): bool
    {
        return $user->managerCan('partnership.update');
    }
    public function detachTownship(IsManagerInterface $user, Partnership $partnership, Township $township): bool
    {
        return $user->managerCan('partnership.update');
    }
}
