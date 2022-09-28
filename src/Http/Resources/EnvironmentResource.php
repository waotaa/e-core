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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'name' => $this->name,
            'slug' => $this->slug,

            'description_header' => $this->description_header,
            'description' => $this->description,

            'logo' => $this->logo,
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
//            'featured_association' => OwnerResource::make($this->featuredAssociation),

            'contact' => ContactResource::make($this->contact),
            'featured_organisations' => OrganisationResource::collection($this->featuredOrganisations),
            'news_items' => NewsItemResource::collection($orderedNewsItems),
        ];
    }
}
