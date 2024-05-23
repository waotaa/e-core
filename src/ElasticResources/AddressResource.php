<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Models\Address;

class AddressResource extends ElasticResource
{
    /** @var Address */
    protected $resource;

    public function toArray()
    {
        return [
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
