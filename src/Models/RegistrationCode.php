<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Observers\RegistrationCodeObserver;

class RegistrationCode extends Model
{
    use HasFactory;

    protected $table = 'registration_codes';

    protected $fillable = [
        'code',
        'label'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(RegistrationCodeObserver::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
