<?php

namespace Vng\EvaCore\ElasticResources\SGR;

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
            'AdresNederland' => $adresNederland,
            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
        ];
    }
}
