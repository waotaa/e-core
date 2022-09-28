<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Vng\EvaCore\Enums\ContactTypeEnum;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Vng\EvaCore\Observers\ContactablesObserver;

class Contactables extends MorphPivot
{
    public $incrementing = true;
    protected $table = 'contactables';

    protected $attributes = [
        'type'
    ];

    protected $fillable = [
        'id',
        'contact_id',
        'contactable_id',
        'contactable_type',
        'type',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function contactable(): MorphTo
    {
        return $this->morphTo('contactable', 'contactable_type', 'contactable_id');
    }

//    public function getContactableClass(): ?string
//    {
//        return Relation::getMorphedModel($this->morphClass);
//    }
//
//    public function findContactable()
//    {
//        var_dump($this->getMorphClass());
//        dd($this->contactable);
//        var_dump($this->contactable_type);
//        dd($this);
//
//        if (is_null($this->getContactableClass())) {
//            return null;
//        }
//        return $this->getContactableClass()::find($this->contactable_id);
//    }

    protected static function boot()
    {
        parent::boot();
        static::observe(ContactablesObserver::class);
    }

    public function setTypeAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['type'] = null;
            return;
        }
        $this->attributes['type'] = (new ContactTypeEnum($value))->getKey();
    }

    public function getTypeAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        if(in_array($value, ContactTypeEnum::keys())) {
            return ContactTypeEnum::$value();
        }
        return $this->attributes['type'];
    }

    public function getRawTypeAttribute()
    {
        return $this->attributes['type'] ?? null;
    }
}

