<?php

namespace Vng\EvaCore\Jobs;

use Illuminate\Support\Facades\Notification;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Notifications\InstrumentExpiredNotificationInterface;

class NotifyInstrumentExpirationJob extends ElasticJob
{
    private Instrument $instrument;

    public function __construct(Instrument $instrument)
    {
        parent::__construct();
        $this->instrument = $instrument;
    }

    public function handle(): void
    {
        $trackers = $this->instrument->instrumentTrackers;

        $managers = $trackers
            ->filter(function (InstrumentTracker $tracker) {
                return $tracker->notifyOfExpiration();
            })
            ->map(function (InstrumentTracker $tracker) {
                return $tracker->manager;
            });

        if ($managers->isEmpty()) {
            return;
        }

        $notification = app(InstrumentExpiredNotificationInterface::class, [
            'instrument' => $this->instrument
        ]);

        Notification::send($managers, $notification);
    }
}
