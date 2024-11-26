<?php

namespace Vng\EvaCore\ElasticResources\SGR\PublicData;

/**
 * An Instrument Resource with some properties withheld.
 * Used for the public index (used on kibana board)
 */
class InstrumentResource extends \Vng\EvaCore\ElasticResources\SGR\InstrumentResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['Contactpersoon']);
        return $resource;
    }
}
