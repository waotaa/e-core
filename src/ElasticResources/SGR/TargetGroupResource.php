<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class TargetGroupResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdDoelgroep' => $this->code,
            'NaamDoelgroep' => Codelijsten::getDoelgroepName($this->code) ?? $this->name,  // AN..200
        ];
    }
}
