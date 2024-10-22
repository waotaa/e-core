<?php

namespace Vng\EvaCore\ElasticResources\Original\Region;

use Vng\EvaCore\ElasticResources\Original\ElasticResource;

class TownshipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' =>  $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
        ];
    }
}
