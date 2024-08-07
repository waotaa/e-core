<?php

namespace Vng\EvaCore\ElasticResources;

class VideoResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'InstrumentWerknemersdienstverlening' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'provider' => $this->provider,
            'video_identifier' => $this->video_identifier,

            'instrument' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument')),
        ];
    }
}
