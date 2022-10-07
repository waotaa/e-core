<?php

namespace Vng\EvaCore\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vng\EvaCore\ElasticResources\ProfessionalResource;
use Vng\EvaCore\Observers\ProfessionalObserver;

class Professional extends SearchableModel implements CanResetPasswordContract
{
    use HasFactory, CanResetPassword, MutationLog;

    protected string $elasticResource = ProfessionalResource::class;

    const STATUSSES = [
        'UNCONFIRMED' => 'Niet bevestigd',
        'CONFIRMED' => 'Bevestigd',
        'ARCHIVED' => 'Gearchiveerd',
        'COMPROMISED' => 'Aangetast',
        'UNKNOWN' => 'Onbekend',
        'RESET_REQUIRED' => 'Reset vereist',
        'FORCE_CHANGE_PASSWORD' => 'Gedwongen wachtwoord wijzigen'
    ];

    protected $fillable = [
        'username',
        'email',
        'email_verified',
        'last_seen_at',
        'enabled',
        'user_status',
    ];

    protected $casts = [
        'email_verified' => 'bool',
        'enabled' => 'bool'
    ];

    protected $dates = [
        'last_seen_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ProfessionalObserver::class);
    }

    public function passwordCanBeReset()
    {
        return in_array($this->user_status, [
            'CONFIRMED',
        ]);
    }

    public function invitationCanBeResend()
    {
        return in_array($this->user_status, [
            'UNCONFIRMED',
//            'CONFIRMED',
            'ARCHIVED',
            'COMPROMISED',
            'UNKNOWN',
//            'RESET_REQUIRED',
            'FORCE_CHANGE_PASSWORD'
        ]);
    }

    public function getStatusTranslatedAttribute(): ?string
    {
        if (in_array($this->user_status, array_keys($this::STATUSSES))) {
            return $this::STATUSSES[$this->user_status];
        }

        return $this->user_status;
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
