<?php

namespace Vng\EvaCore\Notifications;

use Vng\EvaCore\Models\Instrument;

interface InstrumentRevisionNotificationInterface
{
    public static function notify($notifiable, Instrument $instrument);
}