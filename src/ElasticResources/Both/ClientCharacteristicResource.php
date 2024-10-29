<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Helpers\Codelijsten;

class ClientCharacteristicResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdKlantkenmerk' => $this->code,
            'NaamKlantkenmerk' => Codelijsten::getKlantkenmerkName($this->code), // AN..200

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
