<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
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
        ];
    }
}
