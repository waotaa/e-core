<?php

namespace Vng\EvaCore\Jobs;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Notifications\InstrumentRevisionNotificationInterface;

class NotifyInstrumentRevisionJob extends ElasticJob
{
    private Instrument $instrument;

    public function __construct(Instrument $instrument)
    {
        parent::__construct();
        $this->instrument = $instrument;
    }

    public function handle(): void
    {
        /** @var Collection $trackers */
        $trackers = $this->instrument->instrumentTrackers;

        $managers = $trackers
            ->filter(function (InstrumentTracker $tracker) {
                return $tracker->isInstrumentRevisionDay();
            })
            ->map(function (InstrumentTracker $tracker) {
                return $tracker->manager;
            });

        if ($managers->isEmpty()) {
            return;
        }

        $notification = app(InstrumentRevisionNotificationInterface::class, [
            'instrument' => $this->instrument
        ]);

        Notification::send($managers, $notification);
    }
}
