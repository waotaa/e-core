<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SyncAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'status',
    ];

    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    public function origin(): MorphTo
    {
        return $this->morphTo();
    }
}
