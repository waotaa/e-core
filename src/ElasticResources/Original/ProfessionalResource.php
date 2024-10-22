<?php

namespace Vng\EvaCore\ElasticResources\Original;

use Vng\EvaCore\ElasticResources\Original\Professional\EnvironmentResource;

class ProfessionalResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'username' => $this->username,
//            'email' => $this->email, // keep private

            'enabled' => $this->enabled,
            'last_seen_at' => $this->last_seen_at,
            'email_verified' => $this->email_verified,
            'status' => $this->status,

            'ratings_count' => $this->ratings->count(),
//            'ratings' => $this->resource->relationLoaded('ratings') ? RatingResource::many($this->ratings) : null,

            'environment' => EnvironmentResource::one($this->whenLoaded('environment')),
        ];
    }
}
