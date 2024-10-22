<?php

namespace Vng\EvaCore\ElasticResources\Both;

class PartnershipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'Gemeente' => TownshipResource::many($this->whenLoaded('townships')),

            // >> Current
            'id' => $this->id,

            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' => $this->name,
            'slug' => $this->slug,

            'organisation' => OrganisationResource::one($this->whenLoaded('organisation')),
            'townships' => TownshipResource::many($this->whenLoaded('townships')),
        ];
    }
}
