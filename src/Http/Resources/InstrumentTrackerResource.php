<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstrumentTrackerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'voluntary' => $this->voluntary,

            'notified_at' => $this->notified_at,
            'notification_frequency' => $this->notification_frequency,

            'on_modification' => $this->on_modification,
            'on_expiration' => $this->on_expiration,

            'manager' => ManagerResource::make($this->manager),
            'instrument' => InstrumentResource::make($this->instrument)
        ];
    }
}
