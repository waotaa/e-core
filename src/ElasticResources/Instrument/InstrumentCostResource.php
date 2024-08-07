<?php

namespace Vng\EvaCore\ElasticResources\Instrument;

use Vng\EvaCore\ElasticResources\ElasticResource;
use Vng\EvaCore\ElasticResources\Provider\ProviderNameResource;

class InstrumentCostResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamInstrument' => $this->name,

            'BedrTotaalKosten' => [
                'CdMunteenheid' => 'EUR',
                'CdPositiefNegatief' => '1',
                'WaardeBedr' => $this->total_costs,
            ],

            'Aanbieder' => ProviderNameResource::many($this->provider),

            // >> Current
            'name' => $this->name,
            'total_costs' => $this->total_costs,
            'provider' => ProviderNameResource::many($this->provider),
        ];
    }
}
