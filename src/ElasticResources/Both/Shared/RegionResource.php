<?php

namespace Vng\EvaCore\ElasticResources\Both\Shared;

class RegionResource extends \Vng\EvaCore\ElasticResources\Both\RegionResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['contacts']);
        return $resource;
    }
}
