<?php

namespace Vng\EvaCore\ElasticResources;

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
            'url' => $this->url,
            'filename' => $this->filename,

            'instrument' => InstrumentWerknemersdienstverleningResource::many($this->whenLoaded('instruments')),
        ];
    }
}
