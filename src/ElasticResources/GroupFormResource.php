<?php

namespace Vng\EvaCore\ElasticResources;

class GroupFormResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'name' => $this->name,
            'code' => $this->code,
            'custom' => $this->custom,
        ];
    }
}
