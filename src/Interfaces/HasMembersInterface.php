<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface HasMembersInterface
{
    public function members(): BelongsToMany;

    public function hasMember(Model $user): bool;

    public function join(Model $user): self;
}
