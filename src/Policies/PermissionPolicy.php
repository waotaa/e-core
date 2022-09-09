<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;
use Vng\EvaCore\Interfaces\IsManagerInterface;

class PermissionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('viewAny permission');
    }

    public function view(IsManagerInterface $user, Permission $permission): bool
    {
        return $user->managerCan('view permission');
    }

    public function create(IsManagerInterface $user): bool
    {
        return false;
    }

    public function update(IsManagerInterface $user, Permission $permission): bool
    {
        return false;
    }

    public function delete(IsManagerInterface $user, Permission $permission): bool
    {
        return false;
    }

    public function restore(IsManagerInterface $user, Permission $permission): bool
    {
        return false;
    }

    public function forceDelete(IsManagerInterface $user, Permission $permission): bool
    {
        return false;
    }

    public function attachAnyRole(IsManagerInterface $user, Permission $permission): bool
    {
        return false;
    }

    public function attachRole(IsManagerInterface $user, Permission $permission): bool
    {
        return false;
    }

    public function detachRole(IsManagerInterface $user, Permission $permission): bool
    {
        return false;
    }
}
