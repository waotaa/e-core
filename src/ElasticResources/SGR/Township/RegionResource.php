<?php

namespace Vng\EvaCore\ElasticResources\SGR\Township;

use Vng\EvaCore\ElasticResources\Original\ElasticResource;
use function collect;

class RegionResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdArbeidsmarktregio' => substr($this->code, 2, 4),
            'NaamArbeidsmarktregio' => $this->name,
        ];
    }
}
