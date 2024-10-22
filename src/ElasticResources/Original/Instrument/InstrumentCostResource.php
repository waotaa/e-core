<?php

namespace Vng\EvaCore\ElasticResources\Original\Instrument;

use Vng\EvaCore\ElasticResources\Original\ElasticResource;
use Vng\EvaCore\ElasticResources\Original\Provider\ProviderNameResource;

class InstrumentCostResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'name' => $this->name,
            'total_costs' => $this->total_costs,
            'provider' => ProviderNameResource::many($this->provider),
        ];
    }
}
