<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\ElasticResources\SGR\Region\TownshipResource as RegionTownshipResource;
use Vng\EvaCore\Helpers\Codelijsten;

class RegionResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdArbeidsmarktregio' => substr($this->code, 2, 4),
            'NaamArbeidsmarktregio' => Codelijsten::getArbeidsmarktregioName($this->code), // AN..200
//            'NaamArbeidsmarktregio' => $this->name, // API name, codelist is leading

            'Gemeente' => RegionTownshipResource::many($this->townships),
        ];
    }
}
