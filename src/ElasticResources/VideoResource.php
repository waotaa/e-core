<?php

namespace Vng\EvaCore\ElasticResources;

class VideoResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'provider' => $this->provider,
            'video_identifier' => $this->video_identifier,

            'instrument' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
