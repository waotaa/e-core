<?php

namespace Vng\EvaCore\Models\Former;

use Vng\EvaCore\Events\InstrumentAttachedEvent;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DoelgroepInstrument extends Pivot
{
    protected $table = 'doelgroep_instrument';

    public $incrementing = true;

    protected $dispatchesEvents =[
        'saved' => InstrumentAttachedEvent::class,
        'deleted' => InstrumentAttachedEvent::class,
        'restored' => InstrumentAttachedEvent::class,
    ];
}
