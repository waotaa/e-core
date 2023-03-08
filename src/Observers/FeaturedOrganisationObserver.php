<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\FeaturedOrganisation;

class FeaturedOrganisationObserver
{
    public function created(FeaturedOrganisation $featuredOrganisation): void
    {
        $this->syncConnectedElasticResources($featuredOrganisation);
    }

    public function updated(FeaturedOrganisation $featuredOrganisation): void
    {
        $this->syncConnectedElasticResources($featuredOrganisation);
    }

    public function deleted(FeaturedOrganisation $featuredOrganisation): void
    {
        $this->syncConnectedElasticResources($featuredOrganisation);
    }

    public function restored(FeaturedOrganisation $featuredOrganisation): void
    {
        $this->syncConnectedElasticResources($featuredOrganisation);
    }

    private function syncConnectedElasticResources(FeaturedOrganisation $featuredOrganisation): void
    {
        if (!is_null($featuredOrganisation->environment)) {
            ElasticRelatedResourceChanged::dispatch($featuredOrganisation->environment, $featuredOrganisation);
        }
    }
}
