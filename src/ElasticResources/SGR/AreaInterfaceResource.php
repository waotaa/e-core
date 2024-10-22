<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Interfaces\AreaInterface;

class AreaInterfaceResource extends ElasticResource
{
    /** @var AreaInterface */
    protected $resource;

    public function toArray()
    {
        return [
            'NaamGebied' => $this->resource->getName(),
            'TypeGebied' => $this->resource->getType(),
        ];
    }
}
