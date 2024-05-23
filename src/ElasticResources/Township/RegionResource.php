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
            'townships' => collect($this->townships)->pluck('name')->toArray(),
        ];
    }
}
