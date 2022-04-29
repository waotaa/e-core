<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Interfaces\IsOwnerInterface;

class OwnerResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->resource instanceof IsOwnerInterface ? $this->resource->getOwnerType() : null,
            'name' => $this->name,
            'slug' => $this->slug
        ];
    }
}
