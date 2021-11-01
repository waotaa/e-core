<?php

namespace Vng\EvaCore\Events;

use Vng\EvaCore\Models\SearchableModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ElasticRelatedResourceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SearchableModel $model;
    public Model $relatedModel;

    public function __construct(SearchableModel $model, Model $relatedModel)
    {
        $this->model = $model;
        $this->relatedModel = $relatedModel;
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
