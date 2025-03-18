<?php

namespace Vng\EvaCore\Notifications;

use Vng\EvaCore\Models\Instrument;

interface InstrumentModifiedNotificationInterface
{
    public static function notify($notifiable, Instrument $instrument);
}