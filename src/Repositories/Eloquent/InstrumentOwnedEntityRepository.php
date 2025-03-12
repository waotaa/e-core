<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\IsManagerInterface;

trait InstrumentOwnedEntityRepository
{
    public function getQueryOwnedByInstruments(Collection $instruments): Builder
    {
        return $this->addRelatedInstrumentsConditions($this->builder(), $instruments);
    }

    public function addRelatedInstrumentsConditions(Builder $query, Collection $instruments)
    {
        return $query->whereIn('instrument_id', $instruments->pluck('id'));
    }

    public function getQueryOwnedByUser(IsManagerInterface $user): Builder
    {
        return $this->addForUserConditions($this->builder(), $user);
    }

    /**
     * @param Builder $query
     * @param \Vng\EvaCore\Interfaces\IsManagerInterface&Authorizable $user
     * @return Builder
     */
    public function addForUserConditions(Builder $query, IsManagerInterface $user): Builder
    {
        if (!$user->can('viewAll', $this->model)) {
            $organisationIds = $user->getManager()->organisations->pluck('id')->toArray();

            $query->whereHas('instrument', function (Builder $query) use ($organisationIds) {
                $query->whereIn('organisation_id', $organisationIds);
            });
        }

        return $query;
    }
}
