<?php

namespace Vng\EvaCore\ElasticResources\Shared;

class InstrumentResource extends \Vng\EvaCore\ElasticResources\InstrumentResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['contacts']);
        return $resource;
    }
}
