<?php

namespace Vng\EvaCore\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class AccountCreationEmail extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        return $this->buildMailMessage($notifiable->generatedPassword, $verificationUrl);
    }

    /**
     * Get the email notification mail message
     */
    protected function buildMailMessage(string $generatedPassword, string $url): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Eva Account Created'))
            ->line(Lang::get('An Eva account has been created for you.'))
            ->line(Lang::get('Your password is :password', ['password' => $generatedPassword]))
            ->line(Lang::get('After you have verified your email address you can login.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

}
