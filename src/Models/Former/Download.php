<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $table = 'downloads';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
