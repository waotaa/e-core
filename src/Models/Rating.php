<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Observers\RatingObserver;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'author',
        'email',
        'general_score',
        'general_explanation',
        'result_score',
        'result_explanation',
        'execution_score',
        'execution_explanation',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(RatingObserver::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
