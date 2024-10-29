<?php

namespace Vng\EvaCore\ElasticResources\Both;

class VideoResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamVideo' => $this->name,
            'AanbiederVideo' => $this->provider,
            'SleutelVideo' => $this->video_identifier,

            'Instrument' => InstrumentResource::one($this->whenLoaded('instrument')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'provider' => $this->provider,
            'video_identifier' => $this->video_identifier,

            'instrument' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
