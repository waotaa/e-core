<?php

namespace Vng\EvaCore\Models\Former;

use Vng\EvaCore\Events\InstrumentAttachedEvent;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ThemaInstrument extends Pivot
{
    protected $table = 'thema_instrument';

    public $incrementing = true;

    protected $dispatchesEvents =[
        'saved' => InstrumentAttachedEvent::class,
        'deleted' => InstrumentAttachedEvent::class,
        'restored' => InstrumentAttachedEvent::class,
    ];
}
