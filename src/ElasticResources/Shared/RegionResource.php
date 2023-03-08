<?php

namespace Vng\EvaCore\ElasticResources\Shared;

class RegionResource extends \Vng\EvaCore\ElasticResources\RegionResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['contacts']);
        return $resource;
    }
}
