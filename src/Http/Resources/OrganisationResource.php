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
            'organisationable_id' =>$this->organisationable_id,
        ];
    }
}
