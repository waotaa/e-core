<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Helpers\Codelijsten;

class ContactResource extends ElasticResource
{
    public function toArray(): array
    {
        $pivot = $this->resource->pivot;
        $codeType = $pivot ? Codelijsten::getTypeContactPersoonRelatieCode($pivot->type) : null;

        $data = [
            // >> SGR
            'CdTypeContactpersoonRelatie' => $codeType,
            'EmailadresContactpersoon' => $this->email,         // AN..320
            'NaamContactpersoon' => $this->name,                // AN..200
            'TelefoonnummerContactpersoon' => $this->phone,     // AN..14

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'InstrumentWerknemersdienstverlening' => InstrumentWerknemersdienstverleningResource::many($this->whenLoaded('instruments')),
            'Aanbieder' => ProviderResource::many($this->whenLoaded('providers')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'type' => null,
            'label' => $this->resource?->pivot?->label,

            'organisation' => OrganisationResource::one($this->whenLoaded('organisation')),

            'instruments' => InstrumentWerknemersdienstverleningResource::many($this->whenLoaded('instruments')),
            'providers' => ProviderResource::many($this->whenLoaded('providers')),
        ];

        $pivot = $this->resource->pivot;
        if ($pivot) {
            $data['type'] = [
                'key' => $pivot->rawType,
                'name' => $pivot->type,
            ];
        }

        return $data;
    }
}
