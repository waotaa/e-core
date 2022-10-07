<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'username' => $this->username,
            'ratings_count' => $this->ratings->count(),

            'ratings' => RatingResource::collection($this->whenLoaded('ratings')),
            'mutations' => MutationResource::collection($this->mutations),
        ];
    }
}
