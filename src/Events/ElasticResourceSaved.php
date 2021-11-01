<?php

namespace Vng\EvaCore\Events;

use Vng\EvaCore\Models\SearchableModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ElasticResourceSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SearchableModel $model;

    public function __construct(SearchableModel $model)
    {
        $this->model = $model;
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
