<?php

namespace Vng\EvaCore\ElasticResources\Professional;

use Vng\EvaCore\ElasticResources\ElasticResource;

class EnvironmentResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,

            'description_header' => $this->description_header,
            'description' => $this->description,

            'logo' => $this->logo,
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
            'user_pool_id' => $this->user_pool_id,
            'user_pool_client_id' => $this->user_pool_client_id,
            'url' => $this->url,
        ];
    }
}
