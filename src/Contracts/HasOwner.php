<?php

namespace Vng\EvaCore\Contracts;

use Vng\EvaCore\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface HasOwner
{
    public function owner(): MorphTo;

    public function isOwnedByRegion(): bool;

    public function isOwnedByTownship(): bool;

    public function isOwnedByEnvironment(): bool;

    public function hasOwner(): bool;

    public function isOwnedByAdministrator(): bool;

    public function isUserMemberOfOwner(User $user): bool;
}
