<?php

namespace Vng\EvaCore\Contracts;

use Vng\EvaCore\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasMembers
{
    public function members(): MorphMany;

    public function hasMember(User $user): bool;

    public function join(User $user);
}
