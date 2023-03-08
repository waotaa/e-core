<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Neighbourhood;

class NeighbourhoodPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('neighbourhood.viewAny');
    }

    public function view(IsManagerInterface $user, Neighbourhood $neighbourhood)
    {
        return $user->managerCan('neighbourhood.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('neighbourhood.create');
    }

    public function update(IsManagerInterface $user, Neighbourhood $neighbourhood)
    {
        return $user->managerCan('neighbourhood.update');
    }

    public function delete(IsManagerInterface $user, Neighbourhood $neighbourhood)
    {
        return $user->managerCan('neighbourhood.delete');
    }

    public function restore(IsManagerInterface $user, Neighbourhood $neighbourhood)
    {
        return $user->managerCan('neighbourhood.restore');
    }

    public function forceDelete(IsManagerInterface $user, Neighbourhood $neighbourhood)
    {
        return $user->managerCan('neighbourhood.forceDelete');
    }
}
