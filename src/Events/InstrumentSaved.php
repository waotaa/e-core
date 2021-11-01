<?php

namespace Vng\EvaCore\Events;

use Vng\EvaCore\Models\Instrument;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstrumentSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Instrument $instrument;

    public function __construct(Instrument $instrument)
    {
        $this->instrument = $instrument;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
