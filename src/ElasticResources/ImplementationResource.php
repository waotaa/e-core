<?php

namespace Vng\EvaCore\ElasticResources;

class ImplementationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdUitvoeringsvorm' => $this->code,
            'IndEigenToevoegingUitvoeringsvorm' => $this->custom,
            'OmsUitvoeringsvorm' => $this->name, // todo: niet omschrijving maar naam


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
