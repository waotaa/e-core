<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\ModelHelpers\InstrumentHelper;

class InstrumentResource extends ElasticResource
{
    /** @var Instrument */
    protected $resource;

    public function toArray(): array
    {
        $isComplete = InstrumentHelper::create($this->resource)->isComplete();

        return [
            // >> SGR
            'NaamInstrument' => $this->name,                                            // AN..200
            'UuidInstrument' => $this->uuid,                                            // AN36
            'SlugInstrument' => $this->slug,                                            // AN36

            'IndPublicatie' => Codelijsten::getJaNeeIndicatieCode($this->is_active),    // StdIndJN
            'DatBPublicatie' => $this->formatDate($this->publish_from),                 // DATUM
            'DatEPublicatie' => $this->formatDate($this->publish_to),                   // DATUM
            'IndCompleet' => Codelijsten::getJaNeeIndicatieCode($isComplete),           // StdIndJN

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->organisation),

            'DatAangemaakt' => $this->formatDate($this->created_at),    // DATUMTIJD
            'DatGewijzigd' => $this->formatDate($this->updated_at),     // DATUMTIJD
            'DatVerwijderd' => $this->formatDate($this->deleted_at),    // DATUMTIJD

            'CdBereikInstrument' => Codelijsten::getBereikCode($this->resource->getReachSGR()),
            'NaamBereikInstrument' => $this->resource->getReachSGR(),

            'IndLandelijk' => Codelijsten::getJaNeeIndicatieCode($this->resource->isNational()),
            'IndRegionaal' => Codelijsten::getJaNeeIndicatieCode($this->resource->isRegional()),
            'IndLokaal' => Codelijsten::getJaNeeIndicatieCode($this->resource->isLocal()),

            'BeschikbareGebieden' => AreaInterfaceResource::many($this->availableAreas),
            'OmvatteBeschikbareGebieden' => AreaInterfaceResource::many($this->allAvailableAreas),
            'OmvatteBeschikbareGebiedenGemeenten' => AreaInterfaceResource::many($this->allAvailableTownships),

            'Aanbieder' => ProviderResource::one($this->provider),
            'Contactpersoon' => ContactResource::many($this->contacts),
            'Download' => DownloadResource::many($this->downloads),
            'Link' => LinkResource::many($this->links),
            'Registratiecode' => RegistrationCodeResource::many($this->registrationCodes),
            'Uitvoeringslocatie' => LocationResource::many($this->locations),
            'Video' => VideoResource::many($this->videos),

        ];
    }
}
