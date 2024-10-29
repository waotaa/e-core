<?php

namespace Vng\EvaCore\ElasticResources\Both;

class NeighbourhoodResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamWijk' => $this->name,  // AN..200
            'Gemeente' => TownshipResource::one($this->township),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'name' => $this->name,
            'township' => TownshipResource::one($this->township),
        ];
    }
}
