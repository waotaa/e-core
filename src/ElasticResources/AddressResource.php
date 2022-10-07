<?php

namespace Vng\EvaCore\ElasticResources;

class AddressResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'straatnaam' => $this->straatnaam,
            'huisnummer' => $this->huisnummer,
            'postbusnummer' => $this->postbusnummer,
            'antwoordnummer' => $this->antwoordnummer,
            'postcode' => $this->postcode,
            'postcode_digits' => (int) substr($this->postcode, 0, 4),
            'woonplaats' => $this->woonplaats,
        ];
    }
}
