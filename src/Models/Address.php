<?php

namespace Vng\EvaCore\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vng\EvaCore\Observers\AddressObserver;
use Vng\EvaCore\Traits\HasOwner;

class Address extends Model
{
    use HasFactory, HasOwner;

    protected $fillable = [
        'created_at',
        'updated_at',

        'name',
        'straatnaam',
        'huisnummer',
        'huisnummertoevoeging',
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

    protected static function newFactory()
    {
        return AddressFactory::new();
    }

    public function getLabelAttribute()
    {
        if (!$this->getAttribute('name')) {
            return $this->getAddressLineAttribute();
        }
        return $this->getAttribute('name');
    }

    public function isStraatAdres()
    {
        return !$this->isAntwoordNrAdres() && !$this->isPostbusAdres();
    }

    public function isPostbusAdres()
    {
        return !!$this->getAttribute('postbusnummer');
    }

    public function isAntwoordNrAdres()
    {
        return !!$this->getAttribute('antwoordnummer');
    }

    public function getAddressLineAttribute()
    {
        $result = $this->getAttribute('straatnaam');
        if ($this->getAttribute('huisnummer')) {
            $result .= ' ' . $this->getAttribute('huisnummer');
        }

        if ($this->getAttribute('huisnummertoevoeging')) {
            $result .= ' ' . $this->getAttribute('huisnummertoevoeging');
        }

        if ($this->getAttribute('woonplaats')) {
            $result .= $result ? ', ' : '';
            $result .= $this->getAttribute('woonplaats');
        }
        return $result;
    }

    public function getCompleetHuisnummerAttribute()
    {
        if (!$this->getAttribute('huisnummer')) {
            return null;
        }
        $huisnummer = $this->getAttribute('huisnummer');
        if ($this->getAttribute('huisnummertoevoeging')) {
            $huisnummer .= ' ' . $this->getAttribute('huisnummertoevoeging');
        }
        return $huisnummer;
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
