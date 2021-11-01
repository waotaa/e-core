<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Instrument extends Model
{
    use SoftDeletes;

    protected $table = 'instrumenten';

    protected $with = ['gender', 'afstandTotWerk'];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });

        self::saving(function ($model) {
            if (!app()->runningInConsole() && request()->user()->is_admin != true) {
                $model->owner_id = request()->user()->role->access_id;
                $model->owner_type = request()->user()->role->access_type;
            }
        });

        self::deleted(function ($model) {
            $model->status = false;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aanbieder()
    {
        return $this->belongsTo(Aanbieder::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function afstandTotWerk()
    {
        return $this->belongsToMany(AfstandTotWerk::class, 'afstand_tot_werk_instrument', 'instrument_id', 'afstand_tot_werk_id')->withTimestamps()->using(AfstandTotWerkInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function arbeidsvermogen()
    {
        return $this->belongsToMany(Arbeidsvermogen::class, 'arbeidsvermogen_instrument', 'instrument_id', 'arbeidsvermogen_id')->withTimestamps()->using(ArbeidsvermogenInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dienstverband()
    {
        return $this->belongsToMany(Dienstverband::class, 'dienstverband_instrument', 'instrument_id', 'dienstverband_id')->withTimestamps()->using(DienstverbandInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function gender()
    {
        return $this->belongsToMany(Gender::class, 'gender_instrument', 'instrument_id', 'gender_id')->withTimestamps()->using(GenderInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function gezinssituatie()
    {
        return $this->belongsToMany(Gezinssituatie::class, 'gezinssituatie_instrument', 'instrument_id', 'gezinssituatie_id')->withTimestamps()->using(GezinssituatieInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function leeftijdgroep()
    {
        return $this->belongsToMany(Leeftijdgroep::class, 'leeftijdgroep_instrument', 'instrument_id', 'leeftijdgroep_id')->withTimestamps()->using(LeeftijdgroepInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function uitkeringstype()
    {
        return $this->belongsToMany(Uitkeringstype::class, 'uitkeringstype_instrument', 'instrument_id', 'uitkeringstype_id')->withTimestamps()->using(UitkeringstypeInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thema()
    {
        return $this->belongsToMany(Thema::class, 'thema_instrument', 'instrument_id', 'thema_id')->withTimestamps()->using(ThemaInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function doelgroep()
    {
        return $this->belongsToMany(Doelgroep::class, 'doelgroep_instrument', 'instrument_id', 'doelgroep_id')->withTimestamps()->using(DoelgroepInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fase()
    {
        return $this->belongsToMany(Fase::class, 'fase_instrument', 'instrument_id', 'fase_id')->withTimestamps()->using(FaseInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function migratieachtergrond()
    {
        return $this->belongsToMany(Fase::class, 'migratieachtergrond_instrument', 'instrument_id', 'migratieachtergrond_id')->withTimestamps()->using(MigratieAchtergrondInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function regios()
    {
        return $this->belongsToMany(Regio::class, 'regio_instrument', 'instrument_id', 'regio_id')->withTimestamps()->using(RegioInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function gemeentes()
    {
        return $this->belongsToMany(Gemeente::class, 'gemeente_instrument', 'instrument_id', 'gemeente_id')->withTimestamps()->using(GemeenteInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tegels()
    {
        return $this->belongsToMany(Tegel::class, 'tegel_instrument', 'instrument_id', 'tegel_id')->withTimestamps()->using(TegelInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function themas()
    {
        return $this->belongsToMany(Thema::class, 'thema_instrument', 'instrument_id', 'thema_id')->withTimestamps()->using(ThemaInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
}
