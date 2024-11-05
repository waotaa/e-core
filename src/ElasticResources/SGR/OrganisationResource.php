<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class OrganisationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamInstrumentBeherendeOrganisatie' => $this->name,    // AN..200
            'SlugOrganisatie' => $this->slug,                       // AN..200
            'TypeOrganisatie' => $this->type,
            'CdTypeOrganisatie' => Codelijsten::getTypeOrganisatieCode($this->type),

            'LokalePartij' => LocalPartyResource::one($this->whenLoaded('localParty')),
            'RegionalePartij' => RegionalPartyResource::one($this->whenLoaded('regionalParty')),
            'NationalePartij' => NationalPartyResource::one($this->whenLoaded('nationalParty')),
            'Samenwerking' => PartnershipResource::one($this->whenLoaded('partnership')),

            'Contactpersoon' => ContactResource::many($this->whenLoaded('contacts')),
            'ActieveGebieden' => AreaInterfaceResource::many($this->resource->getAreasActiveInAttribute()),
        ];
    }
}
