<?php

namespace Vng\EvaCore\ElasticResources\Both\Provider;

use Vng\EvaCore\ElasticResources\Both\ElasticResource;

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
