<?php

namespace Vng\EvaCore\ElasticResources;

class AddressResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'straatnaam' => $this->straatnaam,
            'huisnummer' => $this->huisnummer,
            'postbusnummer' => $this->postbusnummer,
            'antwoordnummer' => $this->antwoordnummer,
            'postcode' => $this->postcode,
            'woonplaats' => $this->woonplaats,
        ];
    }
}
