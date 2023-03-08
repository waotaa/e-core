<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface InstrumentOwnedEntityRepositoryInterface extends BaseRepositoryInterface
{
    public function getQueryOwnedByInstruments(Collection $instruments): Builder;
    public function addRelatedInstrumentsConditions(Builder $query, Collection $instruments);
}
