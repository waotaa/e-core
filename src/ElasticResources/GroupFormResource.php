<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Helpers\Codelijsten;

class GroupFormResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdGroepsvorm' => $this->code,
            'IndEigenToevoegingGroepsvorm' => $this->custom,    // StdIndJN

            // Bonus
            'NaamGroepsvorm' => Codelijsten::getGroepsvormName($this->code),    // AN..200

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' => $this->name,
            'code' => $this->code,
            'custom' => $this->custom,
        ];
    }
}
