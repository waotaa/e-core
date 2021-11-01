<?php

namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait HasOwner
{
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function hasOwner(): bool
    {
        return $this->owner !== null;
    }

    public function getOwnerClass(): ?string
    {
        if (!$this->hasOwner()) {
            return null;
        }
        return get_class($this->owner);
    }

    public function isUserMemberOfOwner(User $user): bool
    {
        return $this->hasOwner() && $this->owner->hasMember($user);
    }
}
