<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ValidationInterface
{
    public static function make(Request $request): static;
    public function rules(): array;
    public function getCreationRules($field = null): ?array;
    public function getUpdateRules(Model $model, $field = null): ?array;
}
