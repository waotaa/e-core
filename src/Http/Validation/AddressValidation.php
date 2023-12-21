<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AddressValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'postcode' => [
                'required',
                'regex:/^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i',
            ],
            'woonplaats' => [
                'required'
            ],
            'organisation_id' => [
                'required'
            ],
        ];
    }

    protected function creationRules(): array
    {
        return [
            'huisnummer' => [
                Rule::unique('addresses', 'huisnummer')
                    ->where('postcode', $this->request->input('postcode'))
                    ->where('organisation_id', $this->request->input('organisation_id'))
            ],
            'postcode' => [
                'required',
                'regex:/^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i',
                Rule::unique('addresses', 'postcode')
                    ->where('huisnummer', $this->request->input('huisnummer'))
                    ->where('organisation_id', $this->request->input('organisation_id'))
            ],
        ];
    }

    protected function updateRules(Model $model): array
    {
        return [
            'huisnummer' => [
                Rule::unique('addresses', 'huisnummer')
                    ->where('postcode', $this->request->input('postcode'))
                    ->where('organisation_id', $this->request->input('organisation_id'))
                    ->ignore($model->id)
            ],
            'postcode' => [
                'required',
                'regex:/^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i',
                Rule::unique('addresses', 'postcode')
                    ->where('huisnummer', $this->request->input('huisnummer'))
                    ->where('organisation_id', $this->request->input('organisation_id'))
                    ->ignore($model->id)
            ],
        ];
    }
}
