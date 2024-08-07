<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Helpers\Codelijsten;

class ImplementationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdUitvoeringsvorm' => $this->code,
            'IndEigenToevoegingUitvoeringsvorm' => $this->custom,
            'NaamUitvoeringsvorm' => Codelijsten::getUitvoeringsVormName($this->code),

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
