<?php

namespace Vng\EvaCore\ElasticResources\FormerStructure;

use Vng\EvaCore\ElasticResources\ElasticResource;

class TegelResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->tegel,
            'subtegel' => $this->subtegel,
        ];
    }
}
