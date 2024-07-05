<?php

namespace Vng\EvaCore\ElasticResources;

class TargetGroupResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'IndEigenToevoegingDoelgroep' => $this->custom,
            'OmsDoelgroep' => $this->description,

            // todo: code doelgroep toevoegen?

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
