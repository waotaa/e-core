<?php

namespace Vng\EvaCore\ElasticResources\FormerStructure;

use Vng\EvaCore\ElasticResources\ElasticResource;

class OmschrijvingResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'description' => $this->omschrijving,
        ];
    }
}
