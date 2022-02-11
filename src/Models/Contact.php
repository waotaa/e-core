<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Enums\ContactTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vng\EvaCore\Observers\ContactObserver;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ContactObserver::class);
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

    public function getRawTypeAttribute(): ?string
    {
        return $this->attributes['type'] ?? null;
    }

    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class);
    }

    public function instruments(): MorphToMany
    {
        return $this->morphedByMany(Instrument::class, 'contactable')->using(Contactables::class)->withPivot('type');
    }

    public function providers(): MorphToMany
    {
        return $this->morphedByMany(Provider::class, 'contactable')->using(Contactables::class)->withPivot('type');
    }

    public function regions(): MorphToMany
    {
        return $this->morphedByMany(Region::class, 'contactable')->using(Contactables::class)->withPivot('type');
    }
}
