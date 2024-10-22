<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class TargetGroupResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // todo: methode bepalen. Met codelijst of niet..?
            'IndEigenToevoegingDoelgroep' => Codelijsten::getJaNeeIndicatieCode($this->custom), // StdIndJN
            'CdDoelgroep' => $this->code,
            'NaamDoelgroep' => Codelijsten::getDoelgroepName($this->code),  // AN..200
        ];
    }
}
