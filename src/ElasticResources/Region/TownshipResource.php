<?php

namespace Vng\EvaCore\ElasticResources\Region;

use Vng\EvaCore\ElasticResources\ElasticResource;

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
