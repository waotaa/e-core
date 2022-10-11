<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'name' => $this->name,
            'type' => $this->type,

            'organisationable_type' => $this->organisationable_type,
            'organisationable_id' => $this->organisationable_id,

            'managers' => ManagerResource::collection($this->whenLoaded('managers')),
            'featuringEnvironments' => EnvironmentResource::collection($this->whenLoaded('featuringEnvironments')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
        ];
    }
}
