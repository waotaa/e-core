<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\ElasticResources\Both\Environment\BasicEnvironmentResource;
use Vng\EvaCore\Helpers\Codelijsten;

class OrganisationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
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

            'VertonendeOmgevingen' => BasicEnvironmentResource::many($this->featuringEnvironments), // Not when loaded, always

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,

            'localParty' => LocalPartyResource::one($this->whenLoaded('localParty')),
            'regionalParty' => RegionalPartyResource::one($this->whenLoaded('regionalParty')),
            'nationalParty' => NationalPartyResource::one($this->whenLoaded('nationalParty')),
            'partnership' => PartnershipResource::one($this->whenLoaded('partnership')),

            'featuringEnvironments' => BasicEnvironmentResource::many($this->featuringEnvironments), // Not when loaded, always
            'contacts' => ContactResource::many($this->whenLoaded('contacts')),

            'areasActiveIn' => AreaInterfaceResource::many($this->resource->getAreasActiveInAttribute())
        ];
    }
}
