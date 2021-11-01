<?php

namespace Vng\EvaCore\ElasticResources\FormerStructure;

use Vng\EvaCore\ElasticResources\ElasticResource;

class OwnerResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => get_class($this->resource),
            'name' => $this->naam,
        ];
    }
}
