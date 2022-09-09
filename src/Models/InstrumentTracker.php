<?php

namespace Vng\EvaCore\Models;

use Carbon\Carbon;
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
        'on_modification' => true,
        'on_expiration' => true,
    ];

    protected $fillable = [
        'role',
        'voluntary',
        'notified_at',
        'notification_frequency',

        'on_modification',
        'on_expiration',

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

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

//    public function createdBy()
//    {
//        return $this->belongsTo(Manager::class);
//    }


    // Periodic notification checks

    public function hasPeriodicNotifications()
    {
        $shouldBeNotified = $this->shouldBeNotified();
        $hasPeriodicSignal = $this->hasSignal() || $this->hasSignalForNextPeriod();
        return $shouldBeNotified && $hasPeriodicSignal;
    }

    public function hasSignal()
    {
        return $this->instrumentHasExpired() || $this->instrumentNeedsUpdate();
    }

    public function hasSignalForNextPeriod()
    {
        return $this->instrumentWillExpireNextPeriod() || $this->instrumentNeedsUpdateNextPeriod();
    }

    public function instrumentHasExpired()
    {
        $expirationDay = $this->getExpirationDay();
        return $expirationDay && $expirationDay < Carbon::today();
    }

    public function instrumentWillExpireNextPeriod()
    {
        $expirationDay = $this->getExpirationDay();
        return $expirationDay && !$this->instrumentHasExpired() && $expirationDay < $this->getNextPeriodTresholdDate();
    }

    public function instrumentNeedsUpdate()
    {
        return $this->getUpdateTresholdDate() < Carbon::today();
    }

    public function instrumentNeedsUpdateNextPeriod()
    {
        return !$this->instrumentNeedsUpdate() && $this->getUpdateTresholdDate() < $this->getNextPeriodTresholdDate();
    }


    // Direct notification checks

    public function hasDirectNotification()
    {
        return $this->notifyOfExpiration() || $this->notifyOfModification();
    }

    public function notifyOfExpiration()
    {
        // notify if the instrument expires today
        return $this->getAttribute('on_expiration') && $this->instrumentExpiredToday();
    }

    public function notifyOfModification()
    {
        // notify if the instrument was modified the previous day
        return $this->getAttribute('on_modification') && $this->instrumentModifiedYesterday();
    }

    public function instrumentExpiredToday(): bool
    {
        $expirationDay = $this->getExpirationDay();
        return $expirationDay && $expirationDay->isToday();
    }

    public function instrumentModifiedYesterday()
    {
        $modificationDay = $this->instrument->getAttribute('updated_at');
        if (is_null($modificationDay)) {
            return false;
        }
        return Carbon::create($modificationDay)->isYesterday();
    }


    // Important days

    public function getExpirationDay()
    {
        $lastPublicationDay = $this->instrument->getAttribute('publish_to');
        if (is_null($lastPublicationDay)) {
            return null;
        }
        return Carbon::create($lastPublicationDay)->addDay();
    }

    public function getUpdateTresholdDate()
    {
        $months = $this->manager->months_unupdated_limit ?? 6;
        $updatedAt = $this->instrument->updatedAt;
        return Carbon::create($updatedAt)->addMonths($months);
    }
}
