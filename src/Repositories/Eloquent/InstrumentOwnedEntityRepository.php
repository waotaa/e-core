<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait InstrumentOwnedEntityRepository
{
    public function addRelatedInstrumentsConditions(Builder $query, Collection $instruments)
    {
        return $query->whereIn('instrument_id', $instruments->pluck('id'));
    }

    public function getQueryOwnedByInstruments(Collection $instruments): Builder
    {
        return $this->addRelatedInstrumentsConditions($this->builder(), $instruments);
    }
}
