<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasMembersInterface
{
    public function members(): MorphMany;

    public function hasMember(Model $user): bool;

    public function join(Model $user);
}
