<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;
use Vng\EvaCore\Interfaces\IsManagerInterface;

class RolePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('role.viewAny');
    }

    public function view(IsManagerInterface $user, Role $model): bool
    {
        return $user->managerCan('role.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        return false;
    }

    public function update(IsManagerInterface $user, Role $model): bool
    {
        return false;
    }

    public function delete(IsManagerInterface $user, Role $model): bool
    {
        return false;
    }

    public function restore(IsManagerInterface $user, Role $model): bool
    {
        return false;
    }

    public function forceDelete(IsManagerInterface $user, Role $model): bool
    {
        return false;
    }

    public function attachAnyPermission(IsManagerInterface $user, Role $model): bool
    {
        return false;
    }

    public function attachPermission(IsManagerInterface $user, Role $model): bool
    {
        return false;
    }

    public function detachPermission(IsManagerInterface $user, Role $model): bool
    {
        return false;
    }
}
