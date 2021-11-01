<?php

namespace Tests\Fixtures;

use Vng\EvaCore\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class ModelWithHasOwnerTrait extends Model
{
    use HasOwner;
}
