<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\ElasticResources\SGR\Township\RegionResource as TownshipRegionResource;

class TownshipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdGemeente' => substr($this->code, 2, 4),
            'NaamGemeente' => $this->name,              // AN..200

            'Arbeidsmarktregio' => $this->region ? TownshipRegionResource::one($this->region) : null,
            'Wijk' => NeighbourhoodResource::many($this->neighbourhoods),
        ];
    }
}
