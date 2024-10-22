<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class NationalPartyResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
        ];
    }
}
