<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\ElasticResources\Environment\BasicEnvironmentResource;

class OrganisationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,

            'organisationable_type' => $this->organisationable_type,
            'organisationable_id' => $this->organisationable_id,

            'localParty' => LocalPartyResource::one($this->whenLoaded('localParty')),
            'regionalParty' => RegionalPartyResource::one($this->whenLoaded('regionalParty')),
            'nationalParty' => NationalPartyResource::one($this->whenLoaded('nationalParty')),
            'partnership' => PartnershipResource::one($this->whenLoaded('partnership')),

            'featuringEnvironments' => BasicEnvironmentResource::many($this->whenLoaded('featuringEnvironments')),
            'contacts' => ContactResource::many($this->whenLoaded('contacts')),

            'areasActiveIn' => AreaInterfaceResource::many($this->resource->getAreasActiveInAttribute())
        ];
    }
}
