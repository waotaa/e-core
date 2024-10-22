<?php

namespace Vng\EvaCore\ElasticResources\Both;

class LocalPartyResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'Gemeente' => TownshipResource::one($this->whenLoaded('township')),

            // >> Current
            'id' => $this->id,

            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' => $this->name,
            'slug' => $this->slug,

            'township' => TownshipResource::one($this->whenLoaded('township')),
            'organisation' => OrganisationResource::one($this->whenLoaded('organisation'))
        ];
    }
}
