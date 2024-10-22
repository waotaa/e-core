<?php

namespace Vng\EvaCore\ElasticResources\Both\Region;

use Vng\EvaCore\ElasticResources\Both\ElasticResource;

class TownshipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdGemeente' => substr($this->code, 2, 4),
            'NaamGemeente' => $this->name,

            // >> Current
            'id' => $this->id,
            'name' =>  $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
        ];
    }
}
