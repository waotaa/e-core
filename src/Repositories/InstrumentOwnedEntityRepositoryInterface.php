<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\IsManagerInterface;

interface InstrumentOwnedEntityRepositoryInterface extends BaseRepositoryInterface
{
    public function getQueryOwnedByInstruments(Collection $instruments): Builder;
    public function addRelatedInstrumentsConditions(Builder $query, Collection $instruments);
    public function getQueryOwnedByUser(IsManagerInterface $user): Builder;
    public function addForUserConditions(Builder $query, IsManagerInterface $user): Builder;
}
