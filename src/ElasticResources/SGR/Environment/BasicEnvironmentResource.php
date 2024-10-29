<?php

namespace Vng\EvaCore\ElasticResources\SGR\Environment;

use Vng\EvaCore\ElasticResources\SGR\ElasticResource;

class BasicEnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamOmgeving' => $this->name,
            'SlugOmgeving' => $this->slug,
        ];
    }
}
