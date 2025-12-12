<?php

namespace Vng\EvaCore\Notifications;

use Illuminate\Support\Collection;

interface InstrumentPeriodicSignalNotificationInterface
{
    public static function notify($notifiable, Collection $trackers);
}