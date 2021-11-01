<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
