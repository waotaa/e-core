<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class PartnershipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'Gemeente' => TownshipResource::many($this->whenLoaded('townships')),
        ];
    }
}
