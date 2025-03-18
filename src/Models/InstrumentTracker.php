<?php

namespace Vng\EvaCore\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Vng\EvaCore\Enums\FollowerRoleEnum;
use Vng\EvaCore\Enums\NotificationFrequencyEnum;
use Vng\EvaCore\Traits\HasPeriodicNotifications;

class InstrumentTracker extends Pivot
{
    use HasPeriodicNotifications;

    public $incrementing = true;
    protected $table = 'instrument_trackers';

    protected $attributes = [
        'voluntary' => true,
        'on_modification' => false,
        'on_expiration' => true,
    ];

    protected $fillable = [
        'role',
        'voluntary',                // False for the creator of the instrument, true for voluntary watchers
        'notified_at',              // The last email date
        'notification_frequency',   // The frequency a user wants to be periodically emailed about the instrument

        'on_modification',          // True if the user wants to be directly notified on modification
        'on_expiration',            // True if the user wants to be directly notified when publication expired

        'manager_id',
        'instrument_id'
    ];

    protected $dates = [
        'notified_at',
    ];

    public function setRoleAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['role'] = null;
            return;
        }
        $this->attributes['role'] = (new FollowerRoleEnum($value))->getKey();
    }

    public function getRoleAttribute($value)
    {
        if (is_null($value) || !in_array($value, FollowerRoleEnum::keys())) {
            return null;
        }
        return FollowerRoleEnum::$value();
    }

    public function getRawRoleAttribute()
    {
        return $this->attributes['role'] ?? null;
    }

    public function setNotificationFrequencyAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['notification_frequency'] = null;
            return;
        }
        $this->attributes['notification_frequency'] = (new NotificationFrequencyEnum($value))->getKey();
    }

    public function getNotificationFrequencyAttribute($value)
    {
        if (is_null($value) || !in_array($value, NotificationFrequencyEnum::keys())) {
            return null;
        }
        return NotificationFrequencyEnum::$value();
    }

    public function getRawNotificationFrequencyAttribute()
    {
        return $this->attributes['notification_frequency'] ?? null;
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function getInstrument(): Instrument
    {
        return $this->instrument;
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
    // Periodic notification checks

    public function hasPeriodicNotifications(): bool
    {
        $shouldBeNotified = $this->shouldBeNotified();
        $hasPeriodicSignal = $this->hasSignal() || $this->hasSignalForNextPeriod();
        return $shouldBeNotified && $hasPeriodicSignal;
    }

    public function hasSignal(): bool
    {
        return $this->instrumentHasExpired() || $this->instrumentNeedsUpdate();
    }

    public function hasSignalForNextPeriod(): bool
    {
        return $this->instrumentWillExpireNextPeriod() || $this->instrumentNeedsUpdateNextPeriod();
    }

    public function instrumentHasExpired(): bool
    {
        $expirationDay = $this->getInstrument()->getExpirationDay();
        return $expirationDay && $expirationDay < Carbon::today();
    }

    public function instrumentWillExpireNextPeriod(): bool
    {
        $expirationDay = $this->getInstrument()->getExpirationDay();
        return
            !$this->getInstrument()->instrumentHasExpired() &&
            $expirationDay < $this->getNextPeriodTresholdDate();
    }

    public function instrumentNeedsUpdate(): bool
    {
        return $this->getUpdateThresholdDate() < Carbon::today();
    }

    public function instrumentNeedsUpdateNextPeriod(): bool
    {
        return !$this->instrumentNeedsUpdate() && $this->getUpdateThresholdDate() < $this->getNextPeriodTresholdDate();
    }

    public function isInstrumentRevisionDay(): bool
    {
        return $this->getUpdateThresholdDate()->isToday();
    }

    // Direct notification checks

    public function hasDirectNotification(): bool
    {
        return $this->notifyOfExpiration() || $this->notifyOfModification();
    }

    public function notifyOfExpiration(): bool
    {
        // notify if the instrument expires today
        return $this->getAttribute('on_expiration') && $this->getInstrument()->instrumentExpiredToday();
    }

    public function notifyOfModification(): bool
    {
        // notify if the instrument was modified the previous day
        return $this->getAttribute('on_modification') && $this->getInstrument()->instrumentModifiedYesterday();
    }

    private function getUpdateThresholdDate(): bool|Carbon|null
    {
        $months = $this->manager->getRevisionPreference();
        $modificationDay = $this->getInstrument()->getModificationDay();
        return $modificationDay->addMonths($months);
    }
}
