<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssociateableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'association' => OwnerResource::make($this->association)
        ];
    }
}
