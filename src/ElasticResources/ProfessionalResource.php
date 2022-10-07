<?php

namespace Vng\EvaCore\ElasticResources;

class ProfessionalResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'username' => $this->username,

            'email' => $this->email,
            'enabled' => $this->enabled,
            'last_seen_at' => $this->last_seen_at,
            'email_verified' => $this->email_verified,
            'status' => $this->status,

            'ratings_count' => $this->ratings->count(),
        ];
    }
}
