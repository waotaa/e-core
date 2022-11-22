<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;
use Vng\EvaCore\Models\Instrument;

trait OwnedEntityRepository
{
    public function addOwnerlessCondition(Builder $query): Builder
    {
        return $query->whereNull('organisation_id');
    }

    public function addMultipleOwnerConditions(Builder $query, Collection $organisations): Builder
    {
        $organisations->each(function (OrganisationEntityInterface $organisationEntity) use (&$query) {
            $query->orWhere(function($query) use ($organisationEntity) {
                return $this->addOrganisationCondition($query, $organisationEntity);
            });
        });
        return $query;
    }

    public function addOrganisationCondition(Builder $query, OrganisationEntityInterface $organisationEntity): Builder
    {
        return $query->where('organisation_id', $organisationEntity->getOrganisation()->id);
    }

    public function addForUserConditions(Builder $query, EvaUserInterface $user)
    {
        if ($user->cannot('viewAll', $this->model)) {
            $query = $query->whereNull('organisation_id');
            $query = $this->addMultipleOwnerConditions($query, $user->getAssociations());
        }

        return $query;
    }
}
