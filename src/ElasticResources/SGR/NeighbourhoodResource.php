<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class NeighbourhoodResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamWijk' => $this->name,  // AN..200
            'Gemeente' => TownshipResource::one($this->township),
        ];
    }
}
