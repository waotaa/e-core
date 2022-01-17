<?php

namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait IsMember
{
    public function association(): MorphTo
    {
        return $this->morphTo();
    }

    public function isAssociated(): bool
    {
        return !is_null($this->getAttribute('association'));
    }

    public function getAssociationType(): ?string
    {
        if (!$this->isAssociated()) {
            return null;
        }
        return get_class($this->getAttribute('association'));
    }

    public function isMemberOfRegion(): bool
    {
        return $this->isAssociated() && $this->getAssociationType() === Region::class;
    }

    public function isMemberOfTownship(): bool
    {
        return $this->isAssociated() && $this->getAssociationType() === Township::class;
    }

    public function isMemberOfPartnership(): bool
    {
        return $this->isAssociated() && $this->getAssociationType() === Partnership::class;
    }

    public function usersShareAssociation(EvaUserInterface $user): bool
    {
        return $this->isAssociated() && $this->getAttribute('association')->hasMember($user);
    }
}
