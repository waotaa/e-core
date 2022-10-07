<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'organisationable_type' => $this->organisationable_type,
            'organisationable_id' => $this->organisationable_id,

            'name' => $this->name,
            'type' => $this->type,

            'managers' => ManagerResource::collection($this->whenLoaded('managers')),
            'featuringEnvironments' => EnvironmentResource::collection($this->whenLoaded('featuringEnvironments')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
        ];
    }
}
