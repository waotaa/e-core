<?php

namespace Vng\EvaCore\Notifications;

use Illuminate\Support\Collection;

interface InstrumentDailySignalNotificationInterface
{
    public static function notify($notifiable, Collection $trackers);
}