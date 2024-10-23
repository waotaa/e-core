<?php

namespace Vng\EvaCore\ElasticResources\Both\Shared;

class ProviderResource extends \Vng\EvaCore\ElasticResources\Both\ProviderResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['Contactpersoon']);
        unset($resource['contacts']);
        return $resource;
    }
}
