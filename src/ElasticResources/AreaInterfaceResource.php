<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Interfaces\AreaInterface;

class AreaInterfaceResource extends ElasticResource
{
    /** @var AreaInterface */
    protected $resource;

    public function toArray()
    {
        return [
            'identifier' => $this->resource->getAreaIdentifier(),
            'name' => $this->resource->getName(),
            'type' => $this->resource->getType(),
        ];
    }
}
