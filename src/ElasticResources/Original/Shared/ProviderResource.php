<?php

namespace Vng\EvaCore\ElasticResources\Original\Shared;

class ProviderResource extends \Vng\EvaCore\ElasticResources\Original\ProviderResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['Contactpersoon']);
        unset($resource['contacts']);
        return $resource;
    }
}
