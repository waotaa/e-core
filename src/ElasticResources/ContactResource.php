<?php

namespace Vng\EvaCore\ElasticResources;

class ContactResource extends ElasticResource
{
    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'type' => null
        ];

        $pivot = $this->resource->pivot;
        if ($pivot) {
            $type = $pivot->type;
            $data['type'] = [
                'key' => $pivot->rawType,
                'name' => $type,
            ];
        }

        return $data;
    }
}
