<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class ContactResource extends ElasticResource
{
    public function toArray(): array
    {
        $pivot = $this->resource->pivot;
        $codeType = $pivot ? Codelijsten::getTypeContactPersoonRelatieCode($pivot->type) : null;

        return [
            'NaamContactpersoon' => $this->name,                // AN..200
            'TelefoonnummerContactpersoon' => $this->phone,     // AN..14
            'EmailadresContactpersoon' => $this->email,         // AN..320
            'CdTypeContactpersoonRelatie' => $codeType,
            'OmsContactpersoon' => $this->description,          // AN..10000

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'InstrumentWerknemersdienstverlening' => InstrumentResource::many($this->whenLoaded('instruments')),
            'Aanbieder' => ProviderResource::many($this->whenLoaded('providers')),
        ];
    }
}
