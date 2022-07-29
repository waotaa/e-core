<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Vng\EvaCore\Services\ModelHelpers\NewsItemHelper;

class NewsItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'environment_slug' => $this->environment->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'publish_from' => $this->publish_from,
            'publish_to' => $this->publish_to,
            'publication_date' => $this->publish_from ?: $this->created_at,
            'published' => NewsItemHelper::create($this->resource)->isPublished(),
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'body' => $this->body,
            'teaser' => $this->teaser,
        ];
    }
}
