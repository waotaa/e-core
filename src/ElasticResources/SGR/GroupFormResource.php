<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class GroupFormResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdGroepsvorm' => $this->code,
            'NaamGroepsvorm' => Codelijsten::getGroepsvormName($this->code),                        // AN..200

//            'IndEigenToevoegingGroepsvorm' => Codelijsten::getJaNeeIndicatieCode($this->custom),    // StdIndJN
        ];
    }
}
