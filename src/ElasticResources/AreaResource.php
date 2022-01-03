<?php

namespace Vng\EvaCore\ElasticResources;

class AreaResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->area_type ? [
                'class' => $this->area_type,
                'name' =>  class_basename($this->area_type),
            ] : null
        ];
    }
}
