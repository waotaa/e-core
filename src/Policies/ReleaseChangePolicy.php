<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\ReleaseChange;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReleaseChangePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }
    
    /**
     * @param IsManagerInterface&Authorizable $user
     * @param ReleaseChange $release
     * @return mixed
     */
    public function view(IsManagerInterface $user, ReleaseChange $releaseChange)
    {
        return $user->can('view', $releaseChange->release);
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('release.create');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param ReleaseChange $release
     * @return mixed
     */
    public function update(IsManagerInterface $user, ReleaseChange $releaseChange)
    {
        return $user->can('update', $releaseChange->release);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param ReleaseChange $release
     * @return mixed
     */
    public function delete(IsManagerInterface $user, ReleaseChange $releaseChange)
    {
        return $user->can('update', $releaseChange->release);
    }
}
