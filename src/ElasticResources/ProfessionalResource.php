<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\ElasticResources\Professional\EnvironmentResource;

class ProfessionalResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'username' => $this->username,

            'enabled' => $this->enabled,
            'last_seen_at' => $this->last_seen_at,
            'email_verified' => $this->email_verified,
            'status' => $this->status,

            'ratings_count' => $this->ratings->count(),

            'environment' => EnvironmentResource::one($this->environment),

            // keep private
//            'email' => $this->email,
        ];
    }
}
