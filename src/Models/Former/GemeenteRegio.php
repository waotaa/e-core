<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GemeenteRegio extends Pivot
{
    protected $table = 'gemeente_regio';

    public $incrementing = true;
}
