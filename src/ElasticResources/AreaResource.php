<?php

namespace Vng\EvaCore\ElasticResources;

class AreaResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'type' => $this->area_type,
            'name' => $this->area->name,
        ];
    }
}
