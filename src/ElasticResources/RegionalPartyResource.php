<?php

namespace Vng\EvaCore\ElasticResources;

class RegionalPartyResource extends ElasticResource
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

            'organisation' => OrganisationResource::one($this->whenLoaded('organisation')),
            'region' => RegionResource::one($this->whenLoaded('region')),
        ];
    }
}
