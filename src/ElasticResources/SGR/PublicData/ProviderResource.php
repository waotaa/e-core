<?php

namespace Vng\EvaCore\ElasticResources\SGR\PublicData;

class ProviderResource extends \Vng\EvaCore\ElasticResources\SGR\ProviderResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['Contactpersoon']);
        return $resource;
    }
}
