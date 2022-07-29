<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
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
            $data['type'] = [
                'key' => $pivot->rawType,
                'name' => $pivot->type,
            ];
        }

        return $data;
    }
}
