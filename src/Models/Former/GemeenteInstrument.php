<?php

namespace Vng\EvaCore\Models\Former;

use Vng\EvaCore\Events\InstrumentAttachedEvent;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GemeenteInstrument extends Pivot
{
    protected $table = 'gemeente_instrument';

    public $incrementing = true;

    protected $dispatchesEvents =[
        'saved' => InstrumentAttachedEvent::class,
        'deleted' => InstrumentAttachedEvent::class,
        'restored' => InstrumentAttachedEvent::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function instrument()
    {
        return $this->belongsToMany(Instrument::class, 'gemeente_instrument', 'gemeemte_id', 'instrument_id')->using(GemeenteInstrument::class);
    }
}
