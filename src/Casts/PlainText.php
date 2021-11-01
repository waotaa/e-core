<?php

namespace Vng\EvaCore\Casts;

use Vng\EvaCore\Services\TextEditService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PlainText implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $this->transformToPlainText($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $this->transformToPlainText($value);
    }

    private function transformToPlainText($html): ?string
    {
        if (!is_string($html)) {
            return $html;
        }
        return TextEditService::transformToPlainText($html);
    }
}
