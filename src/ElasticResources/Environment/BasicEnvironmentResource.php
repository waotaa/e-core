<?php

namespace Vng\EvaCore\ElasticResources\Environment;

use Vng\EvaCore\ElasticResources\ElasticResource;

class BasicEnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> Niet in SGR

            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
