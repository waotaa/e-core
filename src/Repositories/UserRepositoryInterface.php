<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Organisation;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function addMultipleSameAssociationCondition(Builder $query, Collection $organisations): Builder;
    public function addSameOrganisationCondition(Builder $query, Organisation $organisation): Builder;
    public function addViewAllCondition(Builder $query): Builder;
    public function addViewSelfCondition(Builder $query, EvaUserInterface $user): Builder;
    public function addViewCreatedByCondition(Builder $query, IsManagerInterface $isManager): Builder;
}
