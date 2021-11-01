<?php

namespace Vng\EvaCore\ElasticResources\FormerStructure;

use Vng\EvaCore\ElasticResources\ElasticResource;

class AanbiederResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'name' => $this->naam,
            'contact_persoon' => $this->contact_persoon,
            'did' => $this->did,
            'email' => $this->email,
            'website' => $this->website,
            'logo_header' => config('app.url') . '/storage/' . $this->logo_header,
            'logo_body' => config('app.url') . '/storage/' . $this->logo_body,
            'logo_color' => $this->logo_color,
            'address' => [
                'straatnaam' => $this->straat,
                'huisnummer' => $this->huisnummer,
                'postcode' => $this->postcode,
                'woonplaats' => $this->plaats,
            ],
        ];
    }
}
