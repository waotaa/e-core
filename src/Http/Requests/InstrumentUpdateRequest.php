<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\InstrumentValidation;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class InstrumentUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getInstrument());
    }

    public function rules(): array
    {
        $instrument = $this->getInstrument();
        if (!$instrument instanceof Instrument) {
            throw new \Exception('Cannot derive instrument from route');
        }
        return InstrumentValidation::make($this)->getUpdateRules($instrument);
    }

    protected function getInstrument()
    {
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = App::make(InstrumentRepositoryInterface::class);
        return $instrumentRepository->find($this->route('instrumentId'));
    }
}
