<?php

namespace Vng\EvaCore\ElasticResources\Both\Township;

use Vng\EvaCore\ElasticResources\Both\ElasticResource;
use function collect;

class RegionResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdArbeidsmarktregio' => substr($this->code, 2, 4),
            'NaamArbeidsmarktregio' => $this->name,

            // >> Current
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'townships' => collect($this->townships)->pluck('name')->toArray(),
        ];
    }
}
