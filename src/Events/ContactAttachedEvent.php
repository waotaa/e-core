<?php

namespace Vng\EvaCore\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactAttachedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MorphPivot $pivot;

    public function __construct(MorphPivot $pivot)
    {
        $this->pivot = $pivot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
