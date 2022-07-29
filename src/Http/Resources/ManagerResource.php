<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'organisations' => OrganisationResource::collection($this->organisations)
        ];
    }
}
