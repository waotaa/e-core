<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Models\Address;

class AddressResource extends ElasticResource
{
    /** @var Address */
    protected $resource;

    public function toArray()
    {
        $adresNederland = [
            'Locatieoms' => $this->name,                // AN..70
            'Postcd' => $this->postcode,                // AN6
            'Woonplaatsnaam' => $this->woonplaats,      // AN..80
        ];

        if ($this->resource->isPostbusAdres()) {
            $adresNederland['Postadres'] = [
                'Postbusnr' => $this->postbusnummer         // N5
            ];
        }
        if ($this->resource->isAntwoordNrAdres()) {
            $adresNederland['Antwoordnradres'] = [
                'Antwoordnummer' => $this->antwoordnummer,  // N5
            ];
        }
        if ($this->resource->isStraatAdres()) {
            $adresNederland['Straatadres'] = [
                'Huisnr' => $this->huisnummer,                      // N..5
                'Huisnrtoevoeging' => $this->huisnummertoevoeging,  // AN..6
                'NaamOpenbareRuimte' => $this->straatnaam,          // AN..80
                'Straatnaam' => $this->straatnaam,                  // AN..24
            ];
        }


        return [
            // >> SGR
            'AdresNederland' => $adresNederland,

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
