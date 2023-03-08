<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Township;

class TownshipPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('township.viewAny');
    }

    public function view(IsManagerInterface $user, Township $township): bool
    {
        return $user->managerCan('township.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        return false;
    }

    public function update(IsManagerInterface $user, Township $township): bool
    {
        return $user->managerCan('township.update');
    }

    public function delete(IsManagerInterface $user, Township $township): bool
    {
        return false;
    }

    public function restore(IsManagerInterface $user, Township $township): bool
    {
        return $user->managerCan('township.restore');
    }

    public function forceDelete(IsManagerInterface $user, Township $township): bool
    {
        return false;
    }
}
