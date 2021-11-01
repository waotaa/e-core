<?php

namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMembers
{
    public function members(): MorphMany
    {
        return $this->morphMany(User::class, 'association');
    }

    public function hasMember(User $user): bool
    {
        return $this->members && $this->members->contains($user->id);
    }

    public function join(User $user)
    {
        return $this->members()->save($user);
    }
}
