<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class ClientCharacteristicResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdKlantkenmerk' => $this->code,
            'NaamKlantkenmerk' => Codelijsten::getKlantkenmerkName($this->code), // AN..200
        ];
    }
}
