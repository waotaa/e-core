<?php

namespace Vng\EvaCore\Repositories\Eloquent;

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
        $organisations->each(function (Organisation $organisation) use (&$query) {
            $query->orWhere(function($query) use ($organisation) {
                return $this->addOrganisationCondition($query, $organisation);
            });
        });
        return $query;
    }

    public function addOrganisationCondition(Builder $query, Organisation $organisation): Builder
    {
        return $query->where('organisation_id', $organisation->id);
    }

    public function addForUserConditions(Builder $query, IsManagerInterface $user): Builder
    {
        if ($user->cannot('viewAll', $this->model)) {
            $query = $query->whereNull('organisation_id');
            $query = $this->addMultipleOwnerConditions($query, $user->getManager()->organisations);
        }

        return $query;
    }

    public function getQueryInstrumentsManagedByUser(IsManagerInterface $user): Builder
    {
        return $this->addForUserConditions($this->builder(), $user);
    }
}
