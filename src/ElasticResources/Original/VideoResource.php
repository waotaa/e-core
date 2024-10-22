<?php

namespace Vng\EvaCore\ElasticResources\Original;

class VideoResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'provider' => $this->provider,
            'video_identifier' => $this->video_identifier,

            'instrument' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
