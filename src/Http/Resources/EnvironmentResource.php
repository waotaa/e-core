<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnvironmentResource extends JsonResource
{
    public function toArray($request)
    {
        $orderedNewsItems = $this->resource->newsItems()->orderBy('id', 'desc')->get();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,

            'description_header' => $this->description_header,
            'description' => $this->description,

            'logo' => $this->logo,
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
            'featured_association' => OwnerResource::make($this->featuredAssociation),

            'contact' => ContactResource::make($this->contact),
            'news_items' => NewsItemResource::collection($orderedNewsItems),
        ];
    }
}
