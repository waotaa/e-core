<?php

namespace Vng\EvaCore\Notifications;

use Vng\EvaCore\Models\InstrumentTracker;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class PeriodicInstrumentUpdate extends Notification
{
    use Queueable;

    protected Collection $trackers;

    public function __construct(Collection $trackers)
    {
        $this->trackers = $trackers;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return $this->buildMailMessage($notifiable);
    }

    public function buildMailMessage($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject(__('Periodic instrument update'))
            ->greeting(__('Hello') . ' ' . $notifiable->name);

        $mailMessage = $this->addTrackerInfo($mailMessage);
        return $mailMessage;
    }

    public function addTrackerInfo(MailMessage $message)
    {
        $currentPeriodTrackers = $this->trackers->filter(function (InstrumentTracker $tracker) {
            return $tracker->hasSignal();
        });

        if (!$currentPeriodTrackers->isEmpty()) {
            $message = $this->addSignalForCurrentPeriod($message);
        }

        $nextPeriodTrackers = $this->trackers->filter(function (InstrumentTracker $tracker) {
            return $tracker->hasSignalForNextPeriod();
        });

        if (!$nextPeriodTrackers->isEmpty()) {
            $message = $this->addSignalsForNextPeriod($message);
        }

        return $message;
    }

    public function addSignalForCurrentPeriod(MailMessage $message)
    {
        $message->line(__('Signals that require direct attention'));

        $this->trackers->each(function (InstrumentTracker $tracker) use (&$message) {
            if($tracker->instrumentHasExpired()) {
                $message = $message->line(__("The instrument - :instrument - has expired", [
                    'instrument' => $tracker->instrument->name
                ]));
            }
            if($tracker->instrumentNeedsUpdate()) {
                $message = $message->line(__('The instrument - :instrument - needs a revision', [
                    'instrument' => $tracker->instrument->name
                ]));
            }
        });
        return $message;
    }

    public function addSignalsForNextPeriod(MailMessage $message)
    {
        $message->line(__('Expected signals for next period'));
        $this->trackers->each(function (InstrumentTracker $tracker) use (&$message) {
            if ($tracker->instrumentWillExpireNextPeriod()) {
                $message = $message->line(__('The instrument - :instrument - will expire next period', [
                    'instrument' => $tracker->instrument->name
                ]));
            }
            if ($tracker->instrumentNeedsUpdateNextPeriod()) {
                $message = $message->line(__('The instrument - :instrument - will need a revision next period', [
                    'instrument' => $tracker->instrument->name
                ]));
            }
        });
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'trackers' => $this->trackers->toArray(),
        ];
    }
}
