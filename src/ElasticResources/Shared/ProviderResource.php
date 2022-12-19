<?php

namespace Vng\EvaCore\ElasticResources\Shared;

class ProviderResource extends \Vng\EvaCore\ElasticResources\ProviderResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['contacts']);
        return $resource;
    }
}
