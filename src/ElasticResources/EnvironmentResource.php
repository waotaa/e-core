<?php

namespace Vng\EvaCore\ElasticResources;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
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
            'user_pool_id' => $this->user_pool_id,
            'user_pool_client_id' => $this->user_pool_client_id,
            'url' => $this->url,

            'professionals' => ProfessionalResource::many($this->professionals),
            'featured_organisations' => OrganisationResource::many($this->featuredOrganisations),
            'featured_areas' => AreaInterfaceResource::many($this->featuredAreas),

            'contact' => ContactResource::one($this->contact),
            'news_items' => NewsItemResource::many($orderedNewsItems),
        ];
    }
}
