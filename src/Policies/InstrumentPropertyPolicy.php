<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Instrument;

abstract class InstrumentPropertyPolicy extends BasePolicy
{
    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Model $model
     * @param Instrument $instrument
     * @return bool
     */
    public function attachInstrument(IsManagerInterface $user, Model $model, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Model $model
     * @param Instrument $instrument
     * @return bool
     */
    public function detachInstrument(IsManagerInterface $user, Model $model, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }
}