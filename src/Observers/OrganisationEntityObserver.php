<?php

namespace Vng\EvaCore\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\AbstractOrganisationBase;
use Vng\EvaCore\Models\Organisation;
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
        Log::debug('registered saved organisation entity');
        $organisation = $this->organisationRepository->associateOrganisationable($model);
        $organisation->save();
    }

    protected function trashed(AbstractOrganisationBase $organisationEntity)
    {
        Log::debug('soft deleted organisation entity');
        /** @var Organisation $organisation */
        $organisation = $organisationEntity->organisation;

        // Check if the organisation is already soft deleted
        if ($organisation && !$organisation->trashed()) {
            // If not, perform a soft delete on the organisation
            $organisation->delete();
        }
    }
}
