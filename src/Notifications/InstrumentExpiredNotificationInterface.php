<?php

namespace Vng\EvaCore\Notifications;

use Vng\EvaCore\Models\Instrument;

interface InstrumentExpiredNotificationInterface
{
    public static function notify($notifiable, Instrument $instrument);
}