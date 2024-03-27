<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Models\Manager;

trait IsManager
{
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }

    public function getManager(): ?Manager
    {
        /** @var ?Manager $manager */
        $manager = $this->manager()->first();
        return $manager;
    }

    public function isAdministrator()
    {
        return $this->getManager()->isAdministrator();
    }

    public function isSuperAdmin()
    {
        return $this->getManager()->isSuperAdmin();
    }

    public function managerCan($permission): bool
    {
        $manager = $this->getManager();
        if (is_null($manager)) {
            throw new \Exception('No manager found on user');
        }

        return $manager->hasPermissionTo($permission);
    }

    public function managerCanAny(array $permissions): bool
    {
        /** @var Manager $manager */
        $manager = $this->manager;
        if (is_null($manager)) {
            throw new \Exception('No manager found on user');
        }
        return $manager->hasAnyPermission($permissions);
    }

    public function managerCant($permission): bool
    {
        return !$this->managerCan($permission);
    }

    public function managerCannot($permission): bool
    {
        return $this->managerCant($permission);
    }
}
