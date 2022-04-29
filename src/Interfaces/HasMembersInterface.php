<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface HasMembersInterface
{
    public function members(): MorphToMany;

    public function hasMember(Model $user): bool;

    public function join(Model $user);
}
