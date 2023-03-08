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
            ->greeting(__('Dear :manager', ['manager' => $notifiable->name]). ',');

        $mailMessage = $this->addTrackerInfo($mailMessage);

        $mailMessage->line(__('This e-mail is a reminder to view the instruments and update them where necessary. In this way Eva remains current.'));
        $mailMessage->line(__('What do you have to do'). ':');
        $mailMessage->line('1.' . __('View the instrument'));
        $mailMessage->line('2.' . __('Check whether the texts and links are still up to date and make any changes'));
        $mailMessage->line('3.' . __('Save the instrument again'));
        $mailMessage->line(__('Thanks for your cooperation') . '!');
        $mailMessage->line(__('Sincerely') . ',');
        $mailMessage->line('Instrumentengids Eva');
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
        $message = $message->line(__('The following instruments have been added to the Eva instrument guide for quite some time.'));

        $this->trackers->each(function (InstrumentTracker $tracker) use (&$message) {
            if($tracker->instrumentHasExpired()) {
                $message = $message->line(__("Instrument :instrument of :provider", [
                    'instrument' => $tracker->instrument->name,
                    'provider' => $tracker->instrument->provider?->name
                ]));
//                $message = $message->line(__("The instrument - :instrument - has expired", [
//                    'instrument' => $tracker->instrument->name
//                ]));
            }
            if($tracker->instrumentNeedsUpdate()) {
                $message = $message->line(__("Instrument :instrument of :provider", [
                    'instrument' => $tracker->instrument->name,
                    'provider' => $tracker->instrument->provider?->name
                ]));
//                $message = $message->line(__('The instrument - :instrument - needs a revision', [
//                    'instrument' => $tracker->instrument->name
//                ]));
            }
        });
        return $message;
    }

    public function addSignalsForNextPeriod(MailMessage $message)
    {
        $message = $message->line(__('The following instruments will be due for a revision in the coming period.'));

        $this->trackers->each(function (InstrumentTracker $tracker) use (&$message) {
            if ($tracker->instrumentWillExpireNextPeriod()) {
                $message = $message->line(__("Instrument :instrument of :provider", [
                    'instrument' => $tracker->instrument->name,
                    'provider' => $tracker->instrument->provider?->name
                ]));

//                $message = $message->line(__('The instrument - :instrument - will expire next period', [
//                    'instrument' => $tracker->instrument->name
//                ]));
            }
            if ($tracker->instrumentNeedsUpdateNextPeriod()) {
                $message = $message->line(__("Instrument :instrument of :provider", [
                    'instrument' => $tracker->instrument->name,
                    'provider' => $tracker->instrument->provider?->name
                ]));
//                $message = $message->line(__('The instrument - :instrument - will need a revision next period', [
//                    'instrument' => $tracker->instrument->name
//                ]));
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
