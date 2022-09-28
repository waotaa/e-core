<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'type' => $this->whenPivotLoaded('contactables', function () {
                return [
                    'key' => $this->pivot->rawType,
                    'name' => $this->pivot->type,
                ];
            }),

            'instruments' => InstrumentResource::collection($this->whenLoaded('instruments')),
            'providers' => ProviderResource::collection($this->whenLoaded('providers'))
        ];

//        $pivot = $this->resource->pivot;
//        if ($pivot) {
//            $data['type'] = [
//                'key' => $pivot->rawType,
//                'name' => $pivot->type,
//            ];
//        }

        return $data;
    }
}
