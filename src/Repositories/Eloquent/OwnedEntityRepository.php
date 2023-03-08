<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Organisation;

trait OwnedEntityRepository
{
    public function addOwnerlessCondition(Builder $query): Builder
    {
        return $query->whereNull('organisation_id');
    }

    public function addMultipleOwnerConditions(Builder $query, Collection $organisations): Builder
    {
        $query->where(function(Builder $query) use ($organisations) {
            $organisations->each(function (Organisation $organisation) use (&$query) {
                $query->orWhere(function(Builder $query) use ($organisation) {
                    return $this->addOrganisationCondition($query, $organisation);
                });
            });
        });
        return $query;
    }

    public function addOrganisationCondition(Builder $query, Organisation $organisation): Builder
    {
        return $query->where('organisation_id', $organisation->id);
    }

    /**
     * @param Builder $query
     * @param \Vng\EvaCore\Interfaces\IsManagerInterface&Authorizable $user
     * @return Builder
     */
    public function addForUserConditions(Builder $query, IsManagerInterface $user): Builder
    {
        if (!$user->can('viewAll', $this->model)) {
            $query->where(function(Builder $query) use ($user) {
                $this
                    ->addMultipleOwnerConditions($query, $user->getManager()->organisations)
                    ->orWhereNull('organisation_id');
            });
        }

        return $query;
    }

    public function getQueryItemsManagedByUser(IsManagerInterface $user): Builder
    {
        return $this->addForUserConditions($this->builder(), $user);
    }
}
