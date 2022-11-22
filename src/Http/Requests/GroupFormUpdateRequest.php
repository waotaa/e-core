<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\GroupFormValidation;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Repositories\GroupFormRepositoryInterface;

class GroupFormUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'groupForm';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getGroupForm());
    }

    public function rules(): array
    {
        $groupForm = $this->getGroupForm();
        if (!$groupForm instanceof GroupForm) {
            throw new \Exception('Cannot derive groupForm from route');
        }
        return GroupFormValidation::make($this)->getUpdateRules($groupForm);
    }

    protected function getGroupForm()
    {
        /** @var GroupFormRepositoryInterface $groupFormRepository */
        $groupFormRepository = App::make(GroupFormRepositoryInterface::class);
        return $groupFormRepository->find($this->getModelId());
    }
}
