<?php

namespace Vng\EvaCore\Contracts;

use Vng\EvaCore\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface IsMember
{
    public function association(): MorphTo;

    public function isAssociated(): bool;

    public function getAssociationType(): ?string;

    public function isMemberOfRegion(): bool;

    public function isMemberOfTownship(): bool;

    public function isMemberOfEnvironment(): bool;

    public function usersShareAssociation(User $user): bool;
}
