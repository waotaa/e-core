<?php

namespace Vng\EvaCore\ElasticResources\Original\Shared;

class RegionResource extends \Vng\EvaCore\ElasticResources\Original\RegionResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['contacts']);
        return $resource;
    }
}
