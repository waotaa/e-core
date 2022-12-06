<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ReflectionClass;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Interfaces\IsMemberInterface;
use Vng\EvaCore\Models\Organisation;

trait HasOwner
{
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function hasOwner(): bool
    {
        return $this->organisation !== null;
    }

    public function getOwnerClass(): ?string
    {
        if (!$this->hasOwner()) {
            return null;
        }
        return get_class($this->organisation);
    }

    public function getOwnerType(): ?string
    {
        if (!$this->hasOwner()) {
            return null;
        }
        return (new ReflectionClass($this->organisation))->getShortName();
    }

    public function isUserMemberOfOwner(IsManagerInterface $user): bool
    {
        return $this->hasOwner() && $this->organisation->hasMember($user->getManager());
    }

    /**
     * Remove after fully migrated to Orchid
     *
     * @deprecated
     * @see organisation()
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
