<?php

namespace Vng\EvaCore\ElasticResources;

class LocationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
