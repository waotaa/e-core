<?php

namespace Vng\EvaCore\ElasticResources\Original;

use Vng\EvaCore\Interfaces\AreaInterface;

class AreaInterfaceResource extends ElasticResource
{
    /** @var AreaInterface */
    protected $resource;

    public function toArray()
    {
        return [
            'identifier' => $this->resource->getAreaIdentifier(),
            'name' => $this->resource->getAreaName(),
            'slug' => $this->resource->getAreaSlug(),
            'type' => $this->resource->getAreaType(),
        ];
    }
}
