<?php

namespace Vng\EvaCore\ElasticResources\Both;

class LinkResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamLink' => $this->label,
            'UrlLink' => $this->url,
            'Instrument' => InstrumentResource::one($this->whenLoaded('instrument')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'label' => $this->label,
            'url' => $this->url,

            'instrument' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
