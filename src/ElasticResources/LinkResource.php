<?php

namespace Vng\EvaCore\ElasticResources;

class LinkResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'label' => $this->label,
            'url' => $this->url,

            'instrument' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument')),
        ];
    }
}
