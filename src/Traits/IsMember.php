<?php

namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait IsMember
{
    public function association(): MorphTo
    {
        return $this->morphTo();
    }

    public function isAssociated(): bool
    {
        return !is_null($this->association);
    }

    public function getAssociationType(): ?string
    {
        if (!$this->isAssociated()) {
            return null;
        }
        return get_class($this->association);
    }

    public function isMemberOfRegion(): bool
    {
        return $this->isAssociated() && $this->getAssociationType() === Region::class;
    }

    public function isMemberOfTownship(): bool
    {
        return $this->isAssociated() && $this->getAssociationType() === Township::class;
    }

    public function isMemberOfEnvironment(): bool
    {
        return $this->isAssociated() && $this->getAssociationType() === Environment::class;
    }

    public function usersShareAssociation(User $user): bool
    {
        return $this->isAssociated() && $this->association->hasMember($user);
    }
}
