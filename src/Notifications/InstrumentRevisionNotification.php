<?php

namespace Vng\EvaCore\Notifications;

use Illuminate\Notifications\Notification;
use Vng\EvaCore\Models\Instrument;

class InstrumentRevisionNotification extends Notification implements InstrumentRevisionNotificationInterface
{
    protected Instrument $instrument;

    public function __construct(Instrument $instrument)
    {
        $this->instrument = $instrument;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Instrument needs revision!',
            'instrument' => $this->instrument,
        ];
    }

    public static function notify($notifiable, Instrument $instrument)
    {
        $notifiable->notify(new self($instrument));
    }
}
