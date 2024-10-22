<?php

namespace Vng\EvaCore\ElasticResources\SGR\Instrument;

use Vng\EvaCore\ElasticResources\SGR\ElasticResource;

class InstrumentTestResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamInstrument' => $this->name,                                            // AN..200
            'UuidInstrument' => $this->uuid,                                            // AN36
        ];
    }
}
