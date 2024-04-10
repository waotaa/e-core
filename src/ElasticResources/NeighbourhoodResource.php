<?php

namespace Vng\EvaCore\ElasticResources;

class NeighbourhoodResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'name' => $this->name,
            'township' => TownshipResource::one($this->township),
        ];
    }
}
