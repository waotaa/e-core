<?php

namespace Vng\EvaCore\Traits;

use Carbon\Carbon;
use DateTime;
use Vng\EvaCore\Enums\NotificationFrequencyEnum;

trait HasPeriodicNotifications
{
    public $notificationAttributes = [
        'notified_at',
        'notification_frequency',
    ];

    public function setNotifyNever(): void
    {
        $this->setAttribute('notification_frequency', null);
    }

    public function setNotifyWeekly(): void
    {
        $this->setAttribute('notification_frequency', NotificationFrequencyEnum::weekly()->getValue());
    }

    public function setNotifyMonthly(): void
    {
        $this->setAttribute('notification_frequency', NotificationFrequencyEnum::monthly()->getValue());
    }

    public function updateNotifiedAt()
    {
        $this->setAttribute('notified_at', Carbon::today());
        $this->save();
    }

    public function shouldBeNotified(): bool
    {
        $nextNotificationDate = $this->getNextNotificationDate();
        if (is_null($nextNotificationDate)) {
            return false;
        }
        return $nextNotificationDate <= Carbon::today();
    }

    public function getNextNotificationDate(): ?Carbon
    {
        if ($this->neverWantsNotification()) {
            return null;
        }

        /** @var ?DateTime $notifiedAt */
        $notifiedAt = $this->getAttribute('notified_at') ?? $this->getAttribute('created_at');
        if (is_null($notifiedAt)) {
            return null;
        }

        $notifiedAt = Carbon::create($notifiedAt);
        if ($this->wantsWeeklyNotification()) {
            return $notifiedAt->addWeek()->startOfWeek();
//            return $notifiedAt->addWeek()->startOfWeek(Carbon::MONDAY);
        }

        // Must be monthly
        return $notifiedAt->addMonth()->startOfMonth();
    }

    public function getNextPeriodTresholdDate()
    {
        if ($this->neverWantsNotification()) {
            return null;
        }

        if ($this->wantsWeeklyNotification()) {
            return $this->getNextNotificationDate()->addWeek();
        }

        // Must be monthly
        return $this->getNextNotificationDate()->addMonth();
    }

    public function neverWantsNotification()
    {
        return is_null($this->getAttribute('notification_frequency'));
    }

    public function wantsWeeklyNotification()
    {
        return NotificationFrequencyEnum::weekly()->equals($this->getAttribute('notification_frequecy'));
    }

    public function wantsMonthlyNotification()
    {
        return NotificationFrequencyEnum::monthly()->equals($this->getAttribute('notification_frequecy'));
    }
}
