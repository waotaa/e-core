<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;

abstract class ModelValidation implements ValidationInterface
{
    abstract public static function rules(): array;
    public static function creationRules(): array
    {
        return [];
    }

    public static function updateRules(Model $model): array
    {
        return [];
    }

    public static function getCreationRules($field = null): ?array
    {
        $rules = array_merge(static::rules(), static::creationRules());
        return static::selectRule($rules, $field);
    }

    public static function getUpdateRules(Model $model, $field = null): ?array
    {
        $rules = array_merge(static::rules(), static::updateRules($model));
        return static::selectRule($rules, $field);
    }

    private static function selectRule(array $collection, $field = null)
    {
        if (is_null($field)) {
            return $collection;
        }
        return $collection[$field] ?: null;
    }
}
