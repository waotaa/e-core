<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;

interface OwnedEntityRepositoryInterface extends BaseRepositoryInterface
{
    public function addOwnerlessCondition(Builder $query): Builder;

    public function addMultipleOwnerConditions(Builder $query, Collection $associations): Builder;

    public function addOwnerCondition(Builder $query, IsOwnerInterface $owner): Builder;

    public function addForUserConditions(Builder $query, EvaUserInterface $user);
}
