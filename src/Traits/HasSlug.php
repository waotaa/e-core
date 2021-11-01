<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug() {
        static::saving(function($model) {
            $model->slug = (string) Str::slug($model->name);
        });
    }
}
