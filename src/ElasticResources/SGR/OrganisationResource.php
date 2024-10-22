<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\ElasticResources\SGR\Environment\BasicEnvironmentResource;

class OrganisationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamInstrumentBeherendeOrganisatie' => $this->name,    // AN..200
            'SlugOrganisatie' => $this->slug,                       // AN..200
            'TypeOrganisatie' => $this->type,

            'LokalePartij' => LocalPartyResource::one($this->whenLoaded('localParty')),
            'RegionalePartij' => RegionalPartyResource::one($this->whenLoaded('regionalParty')),
            'NationalePartij' => NationalPartyResource::one($this->whenLoaded('nationalParty')),
            'Samenwerking' => PartnershipResource::one($this->whenLoaded('partnership')),

            'Contactpersoon' => ContactResource::many($this->whenLoaded('contacts')),
        ];
    }
}
