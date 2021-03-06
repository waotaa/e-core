<?php

namespace Vng\EvaCore\ElasticResources;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
    {
//        $orderedNewsItems = $this->resource->newsItems()->orderBy('publicated_from', 'desc')->get();
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
            'featured_association' => OwnerResource::one($this->featuredAssociation),

            'contact' => ContactResource::one($this->contact),
            'news_items' => NewsItemResource::many($orderedNewsItems),
        ];
    }
}
