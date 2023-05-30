<?php

namespace Vng\EvaCore\ElasticResources;

class GroupFormResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'custom' => $this->custom,
        ];
    }
}
