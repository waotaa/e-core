<?php

namespace Vng\EvaCore\ElasticResources\Original;

class NeighbourhoodResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'name' => $this->name,
            'township' => TownshipResource::one($this->township),
        ];
    }
}
