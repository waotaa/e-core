<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\InstrumentTypeValidation;
use Vng\EvaCore\Models\InstrumentType;
use Vng\EvaCore\Repositories\InstrumentTypeRepositoryInterface;

class InstrumentTypeUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getInstrumentType());
    }

    public function rules(): array
    {
        $instrumentType = $this->getInstrumentType();
        if (!$instrumentType instanceof InstrumentType) {
            throw new \Exception('Cannot derive instrumentType from route');
        }
        return InstrumentTypeValidation::make($this)->getUpdateRules($instrumentType);
    }

    protected function getInstrumentType()
    {
        /** @var InstrumentTypeRepositoryInterface $instrumentTypeRepository */
        $instrumentTypeRepository = App::make(InstrumentTypeRepositoryInterface::class);
        return $instrumentTypeRepository->find($this->route('instrumentTypeId'));
    }
}
