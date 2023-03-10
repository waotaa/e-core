<?php

namespace Vng\EvaCore\ElasticResources;

class OrganisationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'slug' => $this->slug,

            'localParty' => LocalPartyResource::one($this->localParty),
            'regionalParty' => RegionalPartyResource::one($this->regionalParty),
            'nationalParty' => NationalPartyResource::one($this->nationalParty),
            'partnership' => PartnershipResource::one($this->partnership),

            'areasActiveIn' => AreaInterfaceResource::many($this->resource->getAreasActiveInAttribute())
        ];
    }
}
