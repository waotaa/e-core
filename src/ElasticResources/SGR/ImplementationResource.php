<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class ImplementationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdUitvoeringsvorm' => $this->code,
            'NaamUitvoeringsvorm' => Codelijsten::getUitvoeringsVormName($this->code) ?? $this->name,                  // AN..200

//            'IndEigenToevoegingUitvoeringsvorm' => Codelijsten::getJaNeeIndicatieCode($this->custom),   // StdIndJN
        ];
    }
}
