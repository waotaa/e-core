<?php

namespace Vng\EvaCore\ElasticResources\Township;

use Vng\EvaCore\ElasticResources\ElasticResource;

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
            'townships' => $this->townships->pluck('name'),
        ];
    }
}
