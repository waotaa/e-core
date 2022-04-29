<?php

namespace Vng\EvaCore\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vng\EvaCore\Models\Contact;

class ContactDetachedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $contactable;
    public Contact $contact;

    public function __construct(Contact $contact, Model $contactable)
    {
        $this->contact = $contact;
        $this->contactable = $contactable;
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
