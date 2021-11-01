<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Contracts\IsOwner;

class OwnerResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->resource instanceof IsOwner ? $this->resource->getShortClassname() : null,
            'name' => $this->name,
            'slug' => $this->slug
        ];
    }
}
