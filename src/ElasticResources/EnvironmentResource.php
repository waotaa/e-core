<?php

namespace Vng\EvaCore\ElasticResources;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
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
            'logo_url' => $this->logo_url,
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
            'user_pool_id' => $this->user_pool_id,
            'user_pool_client_id' => $this->user_pool_client_id,
            'url' => $this->url,

            'contact' => ContactResource::one($this->contact),

            'professionals' => ProfessionalResource::many($this->professionals),
            'featured_organisations' => OrganisationResource::many($this->featuredOrganisations),
            'featured_areas' => AreaInterfaceResource::many($this->featuredAreas),

            'news_items' => NewsItemResource::many($orderedNewsItems),

            'organisation' => OrganisationResource::one($this->organisation),
        ];
    }
}
