<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;

interface OwnedEntityRepositoryInterface extends BaseRepositoryInterface
{
    public function addOwnerlessCondition(Builder $query): Builder;

    public function addMultipleOwnerConditions(Builder $query, Collection $organisations): Builder;

    public function addOrganisationCondition(Builder $query, OrganisationEntityInterface $organisationEntity): Builder;

    public function addForUserConditions(Builder $query, EvaUserInterface $user);
}
