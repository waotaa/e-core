<?php

namespace Vng\EvaCore\ElasticResources\Both;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamOmgeving' => $this->name,
            'SlugOmgeving' => $this->slug,
            'OmsKopOmgeving' => $this->description_header,
            'OmgOmgeving' => $this->description,

            'UrlOmgeving' => $this->url,
            'UrlLogo' => $this->logo_url,

            'PrimaireKleur' => $this->color_primary,
            'SecundaireKleur' => $this->color_secondary,

            // Tijdelijk hier. Deze zijn na de rebuild niet meer nodig
            'user_pool_id' => $this->user_pool_id,
            'user_pool_client_id' => $this->user_pool_client_id,

            'GetoondeOrganisaties' => OrganisationResource::many($this->whenLoaded('featuredOrganisations')),
            'GetoondeGebieden' => AreaInterfaceResource::many(
                $this->whenLoaded('featuredOrganisations', fn () => $this->resource->featuredAreas)
            ),

            'Contactpersoon' => ContactResource::one($this->whenLoaded('contact')),
            'Nieuwsbericht' => NewsItemResource::many($this->whenLoaded('newsItems', function () {
                $this->resource->newsItems()->orderBy('id', 'desc')->get();
            })),
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),

            // >> Current

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
//            'user_pool_id' => $this->user_pool_id,
//            'user_pool_client_id' => $this->user_pool_client_id,
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
