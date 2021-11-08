<?php

namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Notifications\AccountCreationEmail;
use Vng\EvaCore\Notifications\ResetPassword;
use Vng\EvaCore\Services\PasswordService;
use DateTime;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

trait UserPropertiesTrait
{
    use IsMember, Notifiable;

    public string $generatedPassword;

//    protected $fillable = [
//        'name',
//        'email',
//        'email_verified_at',
//        'password',
//        'password_updated_at'
//    ];
//
//    protected $hidden = [
//        'password',
//        'remember_token',
//    ];
//
//    /**
//     * The attributes that should be cast to native types.
//     *
//     * @var array
//     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//        'password_updated_at' => 'datetime'
//    ];

    public static function bootUserPropertiesTrait()
    {
        self::creating(function (EvaUserInterface $user) {
            $user->assignRandomPassword();
        });
    }

    public abstract function isSuperAdmin();

    public function assignRandomPassword()
    {
        $password = PasswordService::generatePassword();
        $this->password = Hash::make($password);
        $this->generatedPassword = $password;
    }

    /**
     * Send the account creation notification.
     */
    public function sendAccountCreationNotification(): void
    {
        $this->notify(new AccountCreationEmail());
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function isPasswordExpired(): bool
    {
        $sixMonthsAgo = (new DateTime())->modify('-6 months');
        return $this->password_updated_at < $sixMonthsAgo;
    }

    public function canImpersonate()
    {
        return $this->isSuperAdmin();
    }

    public function canBeImpersonated()
    {
        return !$this->isSuperAdmin();
    }
}
