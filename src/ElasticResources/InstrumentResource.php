<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Models\Instrument;

class InstrumentResource extends ElasticResource
{
    /** @var Instrument */
    protected $resource;

    public function toArray(): array
    {
        return [
            // >> SGR
            // Instrument
            'DatBPublicatie' => $this->formatDate($this->publish_from),
            'DatEPublicatie' => $this->formatDate($this->publish_to),
            'IndPublicatie' => $this->is_active,
            'NaamInstrument' => $this->name,
            'UuidInstrument' => $this->uuid,

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->organisation),

            'DatAangemaakt' => $this->formatDate($this->created_at),
            'DatGewijzigd' => $this->formatDate($this->updated_at),
            'DatVerwijderd' => $this->formatDate($this->deleted_at),
        ];
    }
}
