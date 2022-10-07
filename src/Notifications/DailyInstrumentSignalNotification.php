<?php

namespace Vng\EvaCore\Notifications;

use Vng\EvaCore\Models\InstrumentTracker;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailyInstrumentSignalNotification extends Notification
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
            ->subject(__('Instrument signals'))
            ->greeting(__('Hello') . ' ' . $notifiable->name)
            ->line(__('There are events on instruments you follow'));

        $mailMessage = $this->addTrackerInfo($mailMessage);
        return $mailMessage;
    }

    public function addTrackerInfo(MailMessage $message)
    {
        $this->trackers->each(function (InstrumentTracker $tracker) use (&$message) {
            if($tracker->instrumentExpiredToday()) {
                $message = $message->line(__('The instrument - :instrument - has expired', [
                    'instrument' => $tracker->instrument->name
                ]));
            }
            if($tracker->instrumentModifiedYesterday()) {
                $message = $message->line(__('The instrument - :instrument - was modified yesterday', [
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
