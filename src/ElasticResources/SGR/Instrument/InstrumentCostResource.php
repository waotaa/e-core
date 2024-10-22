<?php

namespace Vng\EvaCore\ElasticResources\SGR\Instrument;

use Vng\EvaCore\ElasticResources\SGR\ElasticResource;
use Vng\EvaCore\ElasticResources\SGR\Provider\ProviderNameResource;

class InstrumentCostResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamInstrument' => $this->name,

            'BedrTotaalKosten' => [
                'CdMunteenheid' => 'EUR',
                'CdPositiefNegatief' => '1',
                'WaardeBedr' => $this->total_costs,
            ],

            'Aanbieder' => ProviderNameResource::many($this->provider),
        ];
    }
}
