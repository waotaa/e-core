<?php

namespace Vng\EvaCore\ElasticResources;

class NeighbourhoodResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'township' => TownshipResource::one($this->township),
        ];
    }
}
