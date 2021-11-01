<?php

namespace Vng\EvaCore\ElasticResources;

class TargetGroupResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'description'  => $this->description,
            'custom'  => (bool) $this->custom,
        ];
    }
}
