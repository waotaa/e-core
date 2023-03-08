<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Organisation;

interface OwnedEntityRepositoryInterface extends BaseRepositoryInterface
{
    public function addOwnerlessCondition(Builder $query): Builder;

    public function addMultipleOwnerConditions(Builder $query, Collection $organisations): Builder;

    public function addOrganisationCondition(Builder $query, Organisation $organisation): Builder;

    public function addForUserConditions(Builder $query, IsManagerInterface $user): Builder;

    public function getQueryItemsManagedByUser(IsManagerInterface $user): Builder;
}
