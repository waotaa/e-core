<?php

namespace Vng\EvaCore\ElasticResources;

class ContactResource extends ElasticResource
{
    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'type' => null,
            'label' => $this->resource?->pivot?->label,

            'organisation' => OrganisationResource::one($this->organisation),

            'instruments' => InstrumentResource::many($this->resource->relationLoaded('instruments') ? $this->instruments : null),
            'providers' => ProviderResource::many($this->resource->relationLoaded('providers') ? $this->providers : null),
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
