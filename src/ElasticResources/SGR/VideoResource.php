<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class VideoResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamVideo' => $this->name,
            'NaamAanbiederVideo' => $this->provider,
            'SleutelVideo' => $this->video_identifier,

            'Instrument' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
