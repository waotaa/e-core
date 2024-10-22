<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Helpers\Codelijsten;

class TargetGroupResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            // todo: methode bepalen. Met codelijst of niet..?
            'IndEigenToevoegingDoelgroep' => Codelijsten::getJaNeeIndicatieCode($this->custom), // StdIndJN
            'CdDoelgroep' => $this->code,

            // Bonus
            'NaamDoelgroep' => Codelijsten::getDoelgroepName($this->code),  // AN..200

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'description'  => $this->description,
            'code' => $this->code,
            'custom'  => (bool) $this->custom,
        ];
    }
}
