<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Support\Str;

trait HasDynamicSlug
{
    public static function bootHasDynamicSlug() {
        static::saving(function($model) {
            $model->slug = (string) Str::slug($model->name);
        });
    }
}
