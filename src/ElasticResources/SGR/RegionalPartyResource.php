<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class RegionalPartyResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'Arbeidsmarktregio' => RegionResource::one($this->whenLoaded('region')),
        ];
    }
}
