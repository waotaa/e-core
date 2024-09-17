<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Helpers\Codelijsten;
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
            'DatBPublicatie' => $this->formatDate($this->publish_from),                 // DATUM
            'DatEPublicatie' => $this->formatDate($this->publish_to),                   // DATUM
            'IndPublicatie' => Codelijsten::getJaNeeIndicatieCode($this->is_active),    // StdIndJN
            'NaamInstrument' => $this->name,                                            // AN..200
            'UuidInstrument' => $this->uuid,                                            // AN36

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->organisation),

            'DatAangemaakt' => $this->formatDate($this->created_at),    // DATUMTIJD
            'DatGewijzigd' => $this->formatDate($this->updated_at),     // DATUMTIJD
            'DatVerwijderd' => $this->formatDate($this->deleted_at),    // DATUMTIJD

            // Nog invoeren
            'InstrumentSlug' => $this->slug,                                            // AN36
        ];
    }
}
