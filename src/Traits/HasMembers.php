<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMembers
{
    use HasAttributes;

    public abstract function members(): MorphMany;

    public function hasMember(Model $user): bool
    {
        return $this->getAttribute('members') && $this->getAttribute('members')->contains($user->id);
    }

    public function join(Model $user)
    {
        return $this->members()->save($user);
    }
}
