<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class LocalPartyResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'Gemeente' => TownshipResource::one($this->whenLoaded('township')),
        ];
    }
}
