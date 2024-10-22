<?php

namespace Vng\EvaCore\ElasticResources\SGR\Provider;

use Vng\EvaCore\ElasticResources\SGR\ElasticResource;

class ProviderNameResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamAanbieder' => $this->name,
        ];
    }
}
