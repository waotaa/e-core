<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'username' => $this->username,
            'email' => $this->email,

            'enabled' => $this->enabled,
            'last_seen_at' => $this->last_seen_at,
            'email_verified' => $this->email_verified,
            'status' => $this->status,

            'ratings_count' => $this->whenLoaded('ratings', fn() => $this->ratings->count()),
            'ratings' => RatingResource::collection($this->whenLoaded('ratings')),

            'environment' => EnvironmentResource::make($this->whenLoaded('environment')),

            'mutations' => MutationResource::collection($this->mutations),
        ];
    }
}
