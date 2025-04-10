<?php

namespace Vng\EvaCore\Observers;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\AbstractOrganisationBase;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;

class OrganisationObserver
{
    public function __construct(
        protected OrganisationRepositoryInterface $organisationRepository
    )
    {}

    public function deleting(Organisation $model)
    {
        if ($model->isForceDeleting()) {
            Log::debug('hard deleting organisation');
        } else {
            Log::debug('soft deleting organisation');
        }
        if (!$model->isCascadingDelete) {
            // The deletion originated from the OrganisationEntity and needs to cascade to the organisation

            /** @var AbstractOrganisationBase $organisationEntity */
            $organisationEntity = $model->organisation_variant;

            if ($organisationEntity) {
                // flag the organisation entity that the deletion performed on it originated here, so it does not need to cascade back
                $organisationEntity->isCascadingDelete = true;

                if ($model->isForceDeleting()) {
                    Log::debug('cascade hard delete to organisation entity');
                    $organisationEntity->forceDelete();
                } else {
                    Log::debug('cascade soft delete to organisation entity');
                    $organisationEntity->delete();
                }
            }
        }
        $this->syncConnectedElasticResources($model);
    }

    public function restoring(Organisation $model)
    {
        if (!$model->isCascadingRestore) {
            // The restoration originated from the OrganisationEntity and needs to cascade to the organisation

            /** @var AbstractOrganisationBase $organisationEntity */
            $organisationEntity = $model->organisation_variant;
            if ($organisationEntity) {
                // flag the organisation entity that the restoration performed on it originated here, so it does not need to cascade back
                $organisationEntity->isCascadingRestore = true;

                $organisationEntity->restore();
            }
        }
        $this->syncConnectedElasticResources($model);
    }

    private function syncConnectedElasticResources(Organisation $organisation): void
    {
        $organisation->featuringEnvironments->each(
            fn($environment) => ElasticRelatedResourceChanged::dispatch($environment, $organisation)
        );
    }
}
