<?php

namespace Vng\EvaCore\ElasticResources\Both\Environment;

use Vng\EvaCore\ElasticResources\Both\ElasticResource;

class BasicEnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamOmgeving' => $this->name,
            'SlugOmgeving' => $this->slug,

            // >> Current
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
