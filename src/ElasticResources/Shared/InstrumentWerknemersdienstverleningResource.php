<?php

namespace Vng\EvaCore\ElasticResources\Shared;

/**
 * An Instrument Resource with some properties withheld.
 * Used for the public index (used on kibana board)
 */
class InstrumentWerknemersdienstverleningResource extends \Vng\EvaCore\ElasticResources\InstrumentWerknemersdienstverleningResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['contacts']);
        return $resource;
    }
}
