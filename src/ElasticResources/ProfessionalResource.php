<?php

namespace Vng\EvaCore\ElasticResources;

class ProfessionalResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'username' => $this->username,
            'ratings_count' => $this->ratings->count(),
        ];
    }
}
