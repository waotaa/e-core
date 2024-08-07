<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Models\Address;

class AddressResource extends ElasticResource
{
    /** @var Address */
    protected $resource;

    public function toArray()
    {
        $adresNederland = [];

        if ($this->resource->isPostbusAdres()) {
            $adresNederland['Postadres'] = [
                'Postbusnr' => $this->postbusnummer
            ];
        }
        if ($this->resource->isAntwoordNrAdres()) {
            $adresNederland['Antwoordnradres'] = [
                'Antwoordnummer' => $this->antwoordnummer,
            ];
        }
        if ($this->resource->isStraatAdres()) {
            $adresNederland['Straatadres'] = [
                'Huisnr' => $this->huisnummer,
                'Huisnrtoevoeging' => $this->huisnummertoevoeging,
                'NaamOpenbareRuimte' => $this->straatnaam,      // max 80 characters
                'Straatnaam' => $this->straatnaam,              // max 24 characters
            ];
        }

        return [
            // >> SGR
            'AdresNederland' => [
                'Locatieoms' => $this->name,
                'Postcd' => $this->postcode,
                'Woonplaatsnaam' => $this->woonplaats,
                ...$adresNederland
            ],

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'name' => $this->name,

            'straatnaam' => $this->straatnaam,
            'huisnummer' => $this->huisnummer,
            'postbusnummer' => $this->postbusnummer,
            'antwoordnummer' => $this->antwoordnummer,
            'postcode' => $this->postcode,
            'woonplaats' => $this->woonplaats,

            'postcode_digits' => (int) substr($this->postcode, 0, 4),

            'organisation' => OrganisationResource::one($this->resource->relationLoaded('organisation') ? $this->organisation : null)
        ];
    }
}
