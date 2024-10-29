<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Interfaces\AreaInterface;

class AreaInterfaceResource extends ElasticResource
{
    /** @var AreaInterface */
    protected $resource;

    public function toArray()
    {
        return [
            // >> SGR
            'NaamGebied' => $this->resource->getAreaName(),
//            'TypeGebied' => $this->resource->getType(),
            'CdTypeGebied' => Codelijsten::getTypeGebiedCode($this->resource->getAreaTypeSGR()),
            'NaamTypeGebied' => $this->resource->getAreaTypeSGR(),

            // >> Current
            'identifier' => $this->resource->getAreaIdentifier(),
            'name' => $this->resource->getAreaName(),
            'slug' => $this->resource->getAreaSlug(),
            'type' => $this->resource->getAreaType(),
        ];
    }
}
