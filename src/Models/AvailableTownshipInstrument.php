<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Events\InstrumentAttachedEvent;
use Vng\EvaCore\Events\InstrumentDetachedEvent;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AvailableTownshipInstrument extends Pivot
{
    protected $table = 'available_township_instrument';

    public $incrementing = true;

    protected $dispatchesEvents =[
        'created' => InstrumentAttachedEvent::class,
        'updated' => InstrumentAttachedEvent::class,
        'deleted' => InstrumentDetachedEvent::class,
        'restored' => InstrumentAttachedEvent::class,
        'forceDeleted' => InstrumentDetachedEvent::class,
    ];
}
