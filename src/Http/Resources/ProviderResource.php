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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),
            'import_mark' => $this->import_mark,

            'organisation' => OrganisationResource::make($this->organisation),
            'address' => AddressResource::make($this->address),
            'contacts' => ContactResource::collection($this->whenLoaded('contact')),

//            'owner' => OwnerResource::make($this->owner),
            'mutations' => MutationResource::collection($this->mutations),
        ];
    }
}
