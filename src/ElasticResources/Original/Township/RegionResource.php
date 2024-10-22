<?php

namespace Vng\EvaCore\ElasticResources\Original\Township;

use Vng\EvaCore\ElasticResources\Original\ElasticResource;
use function collect;

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
