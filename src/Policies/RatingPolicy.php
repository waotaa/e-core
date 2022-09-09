<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Rating;

class RatingPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('rating.viewAny');
    }

    public function view(IsManagerInterface $user, Rating $rating): bool
    {
        return $user->managerCan('rating.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('rating.create');
    }

    public function update(IsManagerInterface $user, Rating $rating): bool
    {
        return $user->managerCan('rating.update');
    }

    public function delete(IsManagerInterface $user, Rating $rating): bool
    {
        return $user->managerCan('rating.delete');
    }

    public function restore(IsManagerInterface $user, Rating $rating): bool
    {
        return $user->managerCan('rating.restore');
    }

    public function forceDelete(IsManagerInterface $user, Rating $rating): bool
    {
        return $user->managerCan('rating.forceDelete');
    }
}
