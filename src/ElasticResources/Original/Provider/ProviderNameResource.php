<?php

namespace Vng\EvaCore\ElasticResources\Original\Provider;

use Vng\EvaCore\ElasticResources\Original\ElasticResource;

class ProviderNameResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'name' => $this->name,
        ];
    }
}
