<?php

namespace Vng\EvaCore\ElasticResources\Provider;

use Vng\EvaCore\ElasticResources\ElasticResource;

class ProviderNameResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamAanbieder' => $this->name,

            // >> Current
            'name' => $this->name,
        ];
    }
}
