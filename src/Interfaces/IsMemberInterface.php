<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface IsMemberInterface
{
    public function association(): MorphTo;

    public function isAssociated(): bool;

    public function getAssociationType(): ?string;

    public function isMemberOfRegion(): bool;

    public function isMemberOfTownship(): bool;

    public function isMemberOfEnvironment(): bool;

    public function usersShareAssociation(EvaUserInterface $user): bool;
}
