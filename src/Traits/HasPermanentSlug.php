<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Support\Str;

trait HasPermanentSlug
{
    public static function bootHasPermanentSlug() {
        static::creating(function($model) {
            $model->slug = (string) Str::slug($model->name);
        });
    }
}
