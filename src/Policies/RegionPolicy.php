<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Region;

class RegionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('region.viewAny');
    }

    public function view(IsManagerInterface $user, Region $region): bool
    {
        if ($region->hasMember($user)) {
            return true;
        }

        return $user->managerCan('region.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        return false;
    }

    public function update(IsManagerInterface $user, Region $region): bool
    {
        return $user->managerCan('region.update');
    }

    public function delete(IsManagerInterface $user, Region $region): bool
    {
        return false;
    }

    public function restore(IsManagerInterface $user, Region $region): bool
    {
        return $user->managerCan('region.restore');
    }

    public function forceDelete(IsManagerInterface $user, Region $region): bool
    {
        return false;
    }
}
