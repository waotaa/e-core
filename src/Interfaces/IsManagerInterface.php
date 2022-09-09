<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Models\Manager;

interface IsManagerInterface
{
    public function manager(): BelongsTo;

    public function getManager(): ?Manager;

    public function managerCan($abilities): bool;
//    public function managerCan($abilities, $arguments = []): bool;

    public function managerCanAny(array $abilities): bool;
//    public function managerCanAny($abilities, $arguments = []): bool;

    public function managerCant($abilities): bool;
//    public function managerCant($abilities, $arguments = []): bool;

    public function managerCannot($abilities): bool;
//    public function managerCannot($abilities, $arguments = []): bool;
}
