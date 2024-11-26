<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Illuminate\Database\Eloquent\Model;

class BewerkmomentResource extends ElasticResource
{
    /** @var Model */
    protected $resource;

    public function toArray(): array
    {
        return [
            'DatAangemaakt' => $this->formatDate($this->created_at),    // DATUMTIJD
            'DatGewijzigd' => $this->formatDate($this->updated_at),     // DATUMTIJD
            'DatVerwijderd' => $this->formatDate($this->deleted_at),    // DATUMTIJD
        ];
    }
}
