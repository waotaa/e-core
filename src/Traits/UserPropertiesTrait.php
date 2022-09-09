<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Support\Facades\Date;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Notifications\AccountCreationEmail;
use Vng\EvaCore\Notifications\ResetPassword;
use Vng\EvaCore\Observers\UserObserver;
use Vng\EvaCore\Services\PasswordService;
use DateTime;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

trait UserPropertiesTrait
{
    use IsMember, IsManager, Notifiable;

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
        static::observe(UserObserver::class);

        self::creating(function (EvaUserInterface $user) {
            $user->assignRandomPassword();
        });
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getGivenName(): ?string
    {
        [$givenName] = $this->dissectName();
        return $givenName;
    }

    public function getSurName(): ?string
    {
        $names = $this->dissectName();
        return $names[1] ?? null;
    }

    public function dissectName()
    {
        $name = $this->getName();
        if (is_null($name)) {
            return null;
        }
        return explode(' ', $name, 2);
    }

    public function getEmail(): ?string
    {
        return $this->email;
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

    public function setPasswordUpdatedAtToNow()
    {
        $this->password_updated_at = Date::now();
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
