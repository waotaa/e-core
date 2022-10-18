<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'name' => $this->name,
            'givenName' => $this->givenName,
            'surName' => $this->surName,
            'email' => $this->email,
            'months_unupdated_limit' => $this->months_unupdated_limit,

            'created_by' => ManagerResource::make($this->created_by),
            'instrumentTrackers' => InstrumentTrackerResource::collection($this->instrumentTrackers),
            'organisations' => OrganisationResource::collection($this->organisations),
            'roles' => RoleResource::collection($this->roles),

            'mutations' => MutationResource::collection($this->mutations),
        ];
    }
}
