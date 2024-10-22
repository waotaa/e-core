<?php

namespace Vng\EvaCore\ElasticResources\Original\Shared;

/**
 * An Instrument Resource with some properties withheld.
 * Used for the public index (used on kibana board)
 */
class InstrumentResource extends \Vng\EvaCore\ElasticResources\Original\InstrumentResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['contacts']);
        return $resource;
    }
}
