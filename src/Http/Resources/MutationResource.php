<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MutationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'batch_id' => $this->batch_id,
            'name' => $this->name,

            'original' => $this->original,
            'changes' => $this->changes,
            'manager' => ManagerResource::make($this->manager),

            'loggable_type' => $this->loggable_type,
            'loggable_id' => $this->loggable_id,
            'target_type' => $this->target_type,
            'target_id' => $this->target_id,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,

            'fields' => $this->fields,
            'status' => $this->status,
            'exception' => $this->exception,
        ];
    }
}
