<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InstrumentProvider extends Pivot
{
    protected $table = 'instrument_provider';
    public $incrementing = true;
}
