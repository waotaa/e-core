<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Repositories\OwnedEntityRepositoryInterface;

abstract class OwnedEntityRepository extends BaseRepository implements OwnedEntityRepositoryInterface
{
    public function addOwnerlessCondition(Builder $query): Builder
    {
        return $query->whereNull('owner_id');
    }

    public function addMultipleOwnerConditions(Builder $query, Collection $associations): Builder
    {
        $associations->each(function (IsOwnerInterface $owner) use (&$query) {
            $query->orWhere(function($query) use ($owner) {
                return $this->addOwnerCondition($query, $owner);
            });
        });
        return $query;
    }

    public function addOwnerCondition(Builder $query, IsOwnerInterface $owner): Builder
    {
        return $query
            ->where('owner_type', $owner->getOwnerClass())
            ->where('owner_id', $owner->getOwnerId());
    }

    public function addForUserConditions(Builder $query, EvaUserInterface $user)
    {
        $instrumentsQuery = $this->addMultipleOwnerConditions($query, $user->getAssociations());

        if ($user->isSuperAdmin()) {
            $instrumentsQuery = $query->orWhere(function($query) {
                return $this->addOwnerlessCondition($query);
            });
        }

        return $instrumentsQuery;
    }
}
