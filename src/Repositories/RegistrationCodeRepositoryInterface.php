<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\RegistrationCodeCreateRequest;
use Vng\EvaCore\Http\Requests\RegistrationCodeUpdateRequest;
use Vng\EvaCore\Models\RegistrationCode;

interface RegistrationCodeRepositoryInterface extends InstrumentOwnedEntityRepositoryInterface
{
    public function create(RegistrationCodeCreateRequest $request): RegistrationCode;
    public function update(RegistrationCode $registrationCode, RegistrationCodeUpdateRequest $request): RegistrationCode;
}
