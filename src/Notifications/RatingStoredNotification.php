<?php

namespace Vng\EvaCore\Notifications;

use Illuminate\Notifications\Notification;

class RatingStoredNotification extends Notification implements RatingStoredNotificationInterface
{
    protected $rating;

    public function __construct($rating)
    {
        $this->rating = $rating;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new rating has been stored!',
            'rating' => $this->rating,
        ];
    }

    public function notify($notifiable, $rating)
    {
        $notifiable->notify(new self($rating));
    }
}
