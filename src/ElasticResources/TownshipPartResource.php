<?php

namespace Vng\EvaCore\ElasticResources;

class TownshipPartResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' =>  $this->name,
            'township' => TownshipResource::one($this->township),
        ];
    }
}
