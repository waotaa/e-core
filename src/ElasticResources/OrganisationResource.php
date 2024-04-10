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

            'localParty' => LocalPartyResource::one($this->localParty),
            'regionalParty' => RegionalPartyResource::one($this->regionalParty),
            'nationalParty' => NationalPartyResource::one($this->nationalParty),
            'partnership' => PartnershipResource::one($this->partnership),

            'featuringEnvironments' => BasicEnvironmentResource::many($this->featuringEnvironments),
            'contacts' => $this->resource->relationLoaded('contacts') ? ContactResource::many($this->contacts) : null,

            'areasActiveIn' => AreaInterfaceResource::many($this->resource->getAreasActiveInAttribute())
        ];
    }
}
