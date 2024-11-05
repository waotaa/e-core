<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamInstrumentOmgeving' => $this->name,
            'SlugInstrumentOmgeving' => $this->slug,
            'TitelInstrumentOmgeving' => $this->description_header,
            'OmsInstrumentOmgeving' => $this->description,

            'UrlInstrumentOmgeving' => $this->url,
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
