<?php

namespace Vng\EvaCore\ElasticResources;

use Illuminate\Support\Str;
use Vng\EvaCore\Models\Download;

class DownloadResource extends ElasticResource
{
    /** @var Download */
    protected $resource;

    public function toArray()
    {
        $cdn = config('filesystems.cdn');
        $url = $cdn ? Str::finish($cdn, '/') . $this->url : $this->url;

        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'label' => $this->label,
            'url' => $url,
            'filename' => $this->filename,

            'instrument' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
