<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ReflectionClass;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Vng\EvaCore\Interfaces\IsMemberInterface;
use Vng\EvaCore\Models\Organisation;

trait HasOwner
{
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

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

    public function getOwnerType(): ?string
    {
        if (!$this->hasOwner()) {
            return null;
        }
        return (new ReflectionClass($this->owner))->getShortName();
    }

    public function isUserMemberOfOwner(EvaUserInterface $user): bool
    {
        return $this->hasOwner() && $this->owner->hasMember($user);
    }
}
