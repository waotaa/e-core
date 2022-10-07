<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'name' => $this->name,

            'straatnaam' => $this->straatnaam,
            'huisnummer' => $this->huisnummer,
            'postbusnummer' => $this->postbusnummer,
            'antwoordnummer' => $this->antwoordnummer,
            'postcode' => $this->postcode,
            'woonplaats' => $this->woonplaats,

            'postcode_digits' => (int) substr($this->postcode, 0, 4),
        ];
    }
}
