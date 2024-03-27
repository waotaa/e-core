<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Release;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReleasePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('release.viewAny');
    }
    
    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Release $release
     * @return mixed
     */
    public function view(IsManagerInterface $user, Release $release)
    {
        return $user->managerCan('release.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('release.create');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Release $release
     * @return mixed
     */
    public function update(IsManagerInterface $user, Release $release)
    {
        return $user->managerCan('release.update');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Release $release
     * @return mixed
     */
    public function delete(IsManagerInterface $user, Release $release)
    {
        return $user->managerCan('release.delete');
    }
}
