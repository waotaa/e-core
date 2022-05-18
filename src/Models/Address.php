<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vng\EvaCore\Observers\AddressObserver;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'straatnaam',
        'huisnummer',
        'postbusnummer',
        'antwoordnummer',
        'postcode',
        'woonplaats',
    ];

    public static function boot()
    {
        parent::boot();
        static::observe(AddressObserver::class);
    }

    public function getLabelAttribute()
    {
        if (!$this->getAttribute('name')) {
            return $this->getAddressLineAttribute();
        }
        return $this->getAttribute('name');
    }

    public function getAddressLineAttribute()
    {
        $result = $this->getAttribute('straatnaam');
        if ($this->getAttribute('huisnummer')) {
            $result .= ' ' . $this->getAttribute('huisnummer');
        }

        if ($this->getAttribute('woonplaats')) {
            $result .= $result ? ', ' : '';
            $result .= $this->getAttribute('woonplaats');
        }
        return $result;
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
