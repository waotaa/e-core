<?php

namespace Vng\EvaCore\ElasticResources\Instrument;

use Vng\EvaCore\ElasticResources\ElasticResource;

class InstrumentTestResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_duration_unit' => $this->total_duration_unit,
            'total_duration_value' => $this->total_duration_value,
            'total_duration_hours' => $this->total_duration_hours, // calculated value
        ];
    }
}
