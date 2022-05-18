<?php

namespace Vng\EvaCore\ElasticResources\Shared;

use Vng\EvaCore\ElasticResources\ElasticResource;
use Vng\EvaCore\ElasticResources\TownshipResource;

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
        ];
    }
}
