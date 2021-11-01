<?php

namespace Vng\EvaCore\ElasticResources;

class RegionResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'color' => $this->color,
            'cooperation_partners' => $this->cooperation_partners,
            'townships' => TownshipResource::many($this->townships),
            'contacts' => ContactResource::many($this->contacts),
        ];
    }
}
