<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'  => $this->name,
            'email'  => $this->email,
            'email_verfied_at'  => $this->email_verfied_at,
            'password_updated_at'  => $this->password_updated_at,
            'manager' => ManagerResource::make($this->manager),

            'associations' => OwnerResource::collection($this->resource->getAssociations()),
            'associateables' => AssociateableResource::collection($this->associateables),
        ];
    }
}
