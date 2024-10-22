<?php

namespace Vng\EvaCore\ElasticResources\SGR\Region;

use Vng\EvaCore\ElasticResources\SGR\ElasticResource;

class TownshipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdGemeente' => substr($this->code, 2, 4),
            'NaamGemeente' => $this->name,
        ];
    }
}
