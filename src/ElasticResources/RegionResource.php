<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\ElasticResources\Region\TownshipResource as RegionTownshipResource;
use Vng\EvaCore\Helpers\Codelijsten;

class RegionResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdArbeidsmarktregio' => substr($this->code, 2, 4),
            'NaamArbeidsmarktregio' => Codelijsten::getArbeidsmarktregioName($this->code),
//            'NaamArbeidsmarktregio' => $this->name, // API name, codelist is leading

            'Gemeente' => RegionTownshipResource::many($this->townships),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,

            'townships' => RegionTownshipResource::many($this->townships),
        ];
    }
}
