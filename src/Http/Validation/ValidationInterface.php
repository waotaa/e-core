<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;

interface ValidationInterface
{
    public static function rules(): array;
    public static function getCreationRules($field = null): ?array;
    public static function getUpdateRules(Model $model, $field = null): ?array;
}
