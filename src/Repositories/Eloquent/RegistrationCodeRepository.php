<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\RegistrationCodeCreateRequest;
use Vng\EvaCore\Http\Requests\RegistrationCodeUpdateRequest;
use Vng\EvaCore\Models\RegistrationCode;
use Vng\EvaCore\Repositories\RegistrationCodeRepositoryInterface;

class RegistrationCodeRepository extends BaseRepository implements RegistrationCodeRepositoryInterface
{
    use InstrumentOwnedEntityRepository;

    public string $model = RegistrationCode::class;

    public function create(RegistrationCodeCreateRequest $request): RegistrationCode
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(RegistrationCode $registrationCode, RegistrationCodeUpdateRequest $request): RegistrationCode
    {
        return $this->saveFromRequest($registrationCode, $request);
    }

    public function saveFromRequest(RegistrationCode $registrationCode, FormRequest $request): RegistrationCode
    {
        $registrationCode->fill([
            'code' => $request->input('code'),
            'label' => $request->input('label'),
        ]);

        $registrationCode->instrument()->associate($request->input('instrument_id'));

        $registrationCode->save();
        return $registrationCode;
    }
}
