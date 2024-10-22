<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class ImplementationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdUitvoeringsvorm' => $this->code,
            'IndEigenToevoegingUitvoeringsvorm' => Codelijsten::getJaNeeIndicatieCode($this->custom),   // StdIndJN
            'NaamUitvoeringsvorm' => Codelijsten::getUitvoeringsVormName($this->code),                  // AN..200
        ];
    }
}
