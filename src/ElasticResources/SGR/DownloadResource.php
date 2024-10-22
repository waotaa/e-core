<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Models\Download;

class DownloadResource extends ElasticResource
{
    /** @var Download */
    protected $resource;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'label' => $this->label,
            'url' => $this->cdnUrl,
            'filename' => $this->filename,

            'instrument' => InstrumentResource::many($this->whenLoaded('instruments')),
        ];
    }
}
