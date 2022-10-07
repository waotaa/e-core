<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProviderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),
            'address' => AddressResource::make($this->address),
            'organisation' => OrganisationResource::make($this->organisation),
            'contacts' => ContactResource::collection($this->whenLoaded('contact')),

            'owner' => OwnerResource::make($this->owner),
            'mutations' => MutationResource::collection($this->mutations),
        ];
    }
}
