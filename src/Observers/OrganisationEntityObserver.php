<?php

namespace Vng\EvaCore\Observers;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Repositories\Eloquent\OrganisationRepository;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;

class OrganisationEntityObserver
{
    public function __construct(
        protected OrganisationRepositoryInterface $organisationRepository
    )
    {}

    public function creating(Model $model): void
    {
        $organisation = $this->organisationRepository->new();
        $organisation->save();
        $model->organisation()->associate($organisation);
    }

    public function saved(Model $model): void
    {
        // optional
        $organisation = $this->organisationRepository->associateOrganisationable($model);
        $organisation->save();
    }
}
