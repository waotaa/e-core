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
        /** @var Organisation $organisation */
        $organisation = $this->organisationRepository->new();
        $organisation->setOrganisationType($model);
        $organisation->save();
        $model->organisation()->associate($organisation);
    }

    public function deleting(AbstractOrganisationBase $model)
    {
        if ($model->isForceDeleting()) {
            Log::debug('hard deleting organisation entity');
        } else {
            Log::debug('soft deleting organisation entity');
        }

        if (!$model->isCascadingDelete) {
            // The deletion originated from the OrganisationEntity and needs to cascade to the organisation

            /** @var Organisation $organisation */
            $organisation = $model->organisation()->withTrashed()->first();
            if ($organisation) {
                // flag the organisation that the deletion performed on it originated here, so it does not need to cascade back
                $organisation->isCascadingDelete = true;

                if ($model->isForceDeleting()) {
                    Log::debug('cascade hard delete to organisation');
                    $organisation->forceDelete();
                } else {
                    Log::debug('cascade soft delete to organisation');
                    $organisation->delete();
                }
            }
        }
    }

    public function restoring(AbstractOrganisationBase $model)
    {
        if (!$model->isCascadingRestore) {
            // The restoration originated from the OrganisationEntity and needs to cascade to the organisation

            /** @var Organisation $organisation */
            $organisation = $model->organisation()->withTrashed()->first();
            if ($organisation) {
                // flag the organisation that the restoration performed on it originated here, so it does not need to cascade back
                $organisation->isCascadingRestore = true;

                $organisation->restore();
            }
        }
    }
}
