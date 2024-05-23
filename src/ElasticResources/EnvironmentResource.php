<?php

namespace Vng\EvaCore\ElasticResources;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

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

            'contact' => ContactResource::one($this->whenLoaded('contact')),

            'professionals' => ProfessionalResource::many($this->whenLoaded('professionals')),
            'featured_organisations' => OrganisationResource::many($this->whenLoaded('featuredOrganisations')),
            'featured_areas' => AreaInterfaceResource::many(
                $this->whenLoaded('featuredOrganisations', fn () => $this->resource->featuredAreas)
            ),

            'news_items' => NewsItemResource::many($this->whenLoaded('newsItems', function () {
                $this->resource->newsItems()->orderBy('id', 'desc')->get();
            })),

            'organisation' => OrganisationResource::one($this->whenLoaded('organisation')),
        ];
    }
}
