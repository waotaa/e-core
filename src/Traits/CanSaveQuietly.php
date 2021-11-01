<?php

namespace Vng\EvaCore\Traits;

trait CanSaveQuietly
{
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
