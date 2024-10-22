<?php

namespace Vng\EvaCore\ElasticResources\Original\Environment;

use Vng\EvaCore\ElasticResources\Original\ElasticResource;

class BasicEnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
