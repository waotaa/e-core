<?php

namespace Vng\EvaCore\Casts;

use Vng\EvaCore\Services\TextEditService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CleanedHtml implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $this->transformHtml($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $this->transformHtml($value);
    }

    private function transformHtml($html): ?string
    {
        if (!is_string($html)) {
            return $html;
        }
        $html = TextEditService::cleanHtml($html);
        $hasNoContent = !TextEditService::hasContent($html) || TextEditService::hasOnlyEmptyParagraphs($html);
        return $hasNoContent ? null : $html;
    }
}
