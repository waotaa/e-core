<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends ResourceCollection
{
    public $collects = AddressResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
