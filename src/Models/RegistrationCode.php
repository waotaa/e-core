<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationCode extends Model
{
    use HasFactory;

    protected $table = 'registration_codes';

    protected $fillable = [
        'code',
        'label'
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
