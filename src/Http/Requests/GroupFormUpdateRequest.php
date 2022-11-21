<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\GroupFormValidation;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Repositories\GroupFormRepositoryInterface;

class GroupFormUpdateRequest extends FormRequest implements FormRequestInterface
{
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
        return $groupFormRepository->find($this->route('groupFormId'));
    }
}
