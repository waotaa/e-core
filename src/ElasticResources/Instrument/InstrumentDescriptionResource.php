<?php

namespace Vng\EvaCore\ElasticResources\Instrument;

/**
 * An Instrument Resource with some properties withheld.
 * Used for sharing instruments
 */
class InstrumentDescriptionResource extends \Vng\EvaCore\ElasticResources\InstrumentResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['application_instructions']);

        unset($resource['total_costs']);
        unset($resource['costs_description']);
        unset($resource['duration_description']);
        unset($resource['intensity_description']);

        unset($resource['contacts']);
        return $resource;
    }
}
