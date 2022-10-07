<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class ModelValidation implements ValidationInterface
{
    public function __construct(
        protected Request $request
    )
    {}

    public static function make(Request $request): static
    {
        return new static($request);
    }

    abstract public function rules(): array;
    protected function creationRules(): array
    {
        return [];
    }

    protected function updateRules(Model $model): array
    {
        return [];
    }

    public function getCreationRules($field = null): ?array
    {
        $rules = array_merge($this->rules(), $this->creationRules());
        return static::selectRule($rules, $field);
    }

    public function getUpdateRules(Model $model, $field = null): ?array
    {
        $rules = array_merge($this->rules(), $this->updateRules($model));
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
