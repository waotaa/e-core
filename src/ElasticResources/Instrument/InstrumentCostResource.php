<?php

namespace Vng\EvaCore\ElasticResources\Instrument;

use Vng\EvaCore\ElasticResources\ElasticResource;
use Vng\EvaCore\ElasticResources\Provider\ProviderNameResource;

class InstrumentCostResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'name' => $this->name,
            'costs' => $this->costs,
            'costs_unit' => $this->costs_unit,
            'provider' => ProviderNameResource::many($this->provider),
//            'providers' => ProviderNameResource::many($this->providers),
        ];
    }
}
