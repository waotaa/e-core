<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Interfaces\AreaInterface;

class AreaInterfaceResource extends ElasticResource
{
    /** @var AreaInterface */
    protected $resource;

    public function toArray()
    {
        return [
            'NaamGebied' => $this->resource->getAreaName(),
//            'TypeGebied' => $this->resource->getType(),
            'CdTypeGebied' => Codelijsten::getTypeGebiedCode($this->resource->getAreaTypeSGR()),
            'NaamTypeGebied' => $this->resource->getAreaTypeSGR(),
        ];
    }
}
