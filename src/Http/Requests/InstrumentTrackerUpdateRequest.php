<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\InstrumentTrackerValidation;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Repositories\InstrumentTrackerRepositoryInterface;

class InstrumentTrackerUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getInstrumentTracker());
    }

    public function rules(): array
    {
        $instrumentTracker = $this->getInstrumentTracker();
        if (!$instrumentTracker instanceof InstrumentTracker) {
            throw new \Exception('Cannot derive instrumentTracker from route');
        }
        return InstrumentTrackerValidation::make($this)->getUpdateRules($instrumentTracker);
    }

    protected function getInstrumentTracker()
    {
        /** @var InstrumentTrackerRepositoryInterface $instrumentTrackerRepository */
        $instrumentTrackerRepository = App::make(InstrumentTrackerRepositoryInterface::class);
        return $instrumentTrackerRepository->find($this->route('environmentId'));
    }
}
