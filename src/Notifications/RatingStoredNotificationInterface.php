<?php

namespace Vng\EvaCore\Notifications;

interface RatingStoredNotificationInterface
{
    public function notify($notifiable, $rating);
}