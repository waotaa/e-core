<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Illuminate\Support\Str;
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
            'NaamInstrument' => $this->name,                                                      // AN..200
            'UuidInstrument' => $this->uuid,                                                      // AN36
            'SlugInstrument' => (string) Str::slug($this->name),                                  // AN36

            'IndPublicatieInstrument' => Codelijsten::getJaNeeIndicatieCode($this->is_active),    // StdIndJN
            'DatBPublicatieInstrument' => $this->formatDate($this->publish_from),                 // DATUM
            'DatEPublicatieInstrument' => $this->formatDate($this->publish_to),                   // DATUM
            'IndCompleet' => Codelijsten::getJaNeeIndicatieCode($isComplete),                     // StdIndJN

            'Bewerkmoment' => BewerkmomentResource::one($this->resource),

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->organisation),
            'Aanbieder' => ProviderResource::one($this->provider),
            'Contactpersoon' => ContactResource::many($this->contacts),
            'Doelgroep' => TargetGroupResource::many($this->targetGroups),
            'Download' => DownloadResource::many($this->downloads),
            'Link' => LinkResource::many($this->links),
            'Registratiecode' => RegistrationCodeResource::many($this->registrationCodes),
            'Uitvoeringslocatie' => LocationResource::many($this->locations),
            'Video' => VideoResource::many($this->videos),
            'WerklandschapTegel' => TileResource::many($this->tiles),

            'CdBereikInstrument' => Codelijsten::getBereikCode($this->resource->getReachSGR()),
            'NaamBereikInstrument' => $this->resource->getReachSGR(),
            'IndLandelijk' => Codelijsten::getJaNeeIndicatieCode($this->resource->isNational()),
            'IndRegionaal' => Codelijsten::getJaNeeIndicatieCode($this->resource->isRegional()),
            'IndLokaal' => Codelijsten::getJaNeeIndicatieCode($this->resource->isLocal()),

            'BeschikbareGebieden' => AreaInterfaceResource::many($this->availableAreas),
            'OmvatteBeschikbareGebieden' => AreaInterfaceResource::many($this->allAvailableAreas),
            'OmvatteBeschikbareGebiedenGemeenten' => AreaInterfaceResource::many($this->allAvailableTownships),
        ];
    }
}
