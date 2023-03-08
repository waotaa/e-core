<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    protected function getModelId()
    {
        if (isset($this->modelName)) {
            if ($this->route()->hasParameter($this->modelName)) {
                return $this->route()->originalParameter($this->modelName);
            }
            if ($this->route()->hasParameter($this->modelName . 'Id')) {
                return $this->route()->originalParameter($this->modelName . 'Id');
            }
        }
        // return the first parameter
        $parameters = $this->route()->originalParameters();
        return reset($parameters);
    }
}
