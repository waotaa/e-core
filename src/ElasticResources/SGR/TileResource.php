<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class TileResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdWerklandschapTegel' => $this->code,
            'NaamWerklandschapTegel' => Codelijsten::getWerklandschapTegelName($this->code), // AN..200
        ];
    }
}
