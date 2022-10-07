<?php

namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Notifications\DailyInstrumentSignalNotification;
use Vng\EvaCore\Notifications\PeriodicInstrumentUpdate;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

trait IsInstrumentWatcher
{
    public $watcherAttributes = [
        'months_unupdated_limit',
    ];

    public function instrumentTrackers(): HasMany
    {
        return $this->hasMany(InstrumentTracker::class);
    }

    public function watchedInstruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_trackers')->using(InstrumentTracker::class);
    }

    public function notifyOfDirectSignals()
    {
        $signalingTrackers = $this->getTriggeredDirectTrackers();
        if (is_null($signalingTrackers) || $signalingTrackers->isEmpty()) {
            return; // nothing to notify
        }
        $this->notify(new DailyInstrumentSignalNotification($signalingTrackers));
    }

    public function getTriggeredDirectTrackers(): ?Collection
    {
        if (is_null($this->instrumentTrackers)) {
            return null;
        }

        return $this->instrumentTrackers->filter(function (InstrumentTracker $tracker) {
            return $tracker->hasDirectNotification();
        });
    }

    public function notifyOfPeriodicSignals()
    {
        $signalingTrackers = $this->getTriggeredPeriodicTrackers();
        if (is_null($signalingTrackers) || $signalingTrackers->isEmpty()) {
            return; // nothing to notify
        }

        $this->notify(new PeriodicInstrumentUpdate($signalingTrackers));

        $signalingTrackers->each(function (InstrumentTracker $tracker) {
            $tracker->updateNotifiedAt();
        });
    }

    public function getTriggeredPeriodicTrackers(): ?Collection
    {
        if (is_null($this->instrumentTrackers)) {
            return null;
        }

        return $this->instrumentTrackers->filter(function (InstrumentTracker $tracker) {
            return $tracker->hasPeriodicNotifications();
        });
    }
}
