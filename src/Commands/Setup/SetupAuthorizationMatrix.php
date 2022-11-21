<?php

namespace Vng\EvaCore\Commands\Setup;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SetupAuthorizationMatrix extends Command
{
    protected $signature = 'eva-core:setup-authorization';
    protected $description = 'Setup or update the roles and permissions as defined in the authorization config';

    public function handle(): int
    {
        $this->info("\n[ Setting up eva authorization matrix ]\n");

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->output->writeln('creating permissions');
        $permissions = $this->createPermissions();
        $this->deleteOutdatedPermissions($permissions);

        $this->output->writeln('creating roles');
        $roles = $this->createRoles();
        $this->deleteOutdatedRoles($roles);

        $this->output->writeln('assigning permissions to roles');
        $this->assignPermissionsToRoles();

        $this->info("\n[ Setting up eva authorization matrix ] - finished!\n");
        return 0;
    }

    private function getAllRoles(): Collection
    {
        return collect(config('authorization.roles'));
    }

    private function getAllPermissionNames()
    {
        $modelPermissions = $this->getModelPermissions();
        $rolePermissions = $this->getRolePermissions();
        return $modelPermissions
            ->merge($rolePermissions)
            ->unique()
            ->sort()
            ->values();
    }

    private function getRolePermissions()
    {
        $roles = $this->getAllRoles();
        return $roles->keys()->map(function (string $roleName) {
            return $this->getPermissionsForRoleKey($roleName);
        })->flatten()->unique();
    }

    private function getModelPermissions()
    {
        $models = collect(config('authorization.model-permissions'));
        return $models->map(function ($modelName) {
            return [
                $modelName.'.viewAny',
                $modelName.'.view',
                $modelName.'.viewAll',
                $modelName.'.create',
                $modelName.'.update',
                $modelName.'.delete',
                $modelName.'.restore',
                $modelName.'.forceDelete'
            ];
        })->flatten();
    }

    private function createPermissions(): Collection
    {
        $this->output->info('ensuring permissions');
        $permissions = $this->getAllPermissionNames();
        return $permissions->map(function ($permission) {
//            $this->output->writeln($permission);
            return $this->createPermission($permission);
        });
    }

    private function createPermission($permission): \Spatie\Permission\Contracts\Permission
    {
        return Permission::findOrCreate($permission);
    }

    private function deleteOutdatedPermissions(Collection $currentPermissions)
    {
        Permission::query()->whereNotIn('name', $currentPermissions->pluck('name'))->delete();
    }

    private function createRoles(): Collection
    {
        $roles = $this->getAllRoles();
        return $roles->keys()->map(function ($role) {
            return $this->createRole($role);
        });
    }

    private function createRole($roleName): \Spatie\Permission\Contracts\Role
    {
        return Role::findOrCreate($roleName);
    }

    private function deleteOutdatedRoles(Collection $currentRoles)
    {
        Role::query()->whereNotIn('name', $currentRoles->pluck('name'))->delete();
    }

    private function assignPermissionsToRoles(): void
    {
        $roles = Role::all();
        $roles->each(function (Role $role) {
            $this->output->writeln('handling role ' . $role->name);
            $this->assignPermissionsToRole($role);
        });
    }

    private function assignPermissionsToRole(Role $role): void
    {
        $permissions = $this->getPermissionsForRoleKey($role->getAttribute('name'));
        $permissionCollection = collect($permissions)
            ->map(fn ($permissionName) => $this->createPermission($permissionName));
        $role->syncPermissions($permissionCollection);
    }

    private function getPermissionsForRoleKey(string $role): array
    {
        return config('authorization.matrix.' . $role, []);
    }
}
