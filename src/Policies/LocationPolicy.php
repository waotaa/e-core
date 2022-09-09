<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Location;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Location $location
     * @return mixed
     */
    public function view(IsManagerInterface $user, Location $location)
    {
        return $user->can('view', $location->instrument);
    }

    public function create(IsManagerInterface $user)
    {
        return true;
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Location $location
     * @return mixed
     */
    public function update(IsManagerInterface $user, Location $location)
    {
        return $user->can('update', $location->instrument);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Location $location
     * @return mixed
     */
    public function delete(IsManagerInterface $user, Location $location)
    {
        return $user->can('update', $location->instrument);
    }
}
