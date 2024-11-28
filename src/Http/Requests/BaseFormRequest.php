<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    protected function getModelId()
    {
        $modelId = $this->getRouteIdParameter($this->modelName);
        if (!is_null($modelId)) {
            return $modelId;
        }
        return $this->getRouteFirstParameter();
    }

    protected function getRouteIdParameter($parameterName): ?string
    {
        if ($this->route()->hasParameter($parameterName)) {
            return $this->route()->originalParameter($parameterName);
        }
        if ($this->route()->hasParameter($parameterName . 'Id')) {
            return $this->route()->originalParameter($parameterName . 'Id');
        }
        return null;
    }

    protected function getRouteFirstParameter()
    {
        $parameters = $this->route()->originalParameters();
        return reset($parameters);
    }
}
