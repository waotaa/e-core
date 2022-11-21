<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\LocationValidation;
use Vng\EvaCore\Models\Location;
use Vng\EvaCore\Repositories\LocationRepositoryInterface;

class LocationUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getLocation());
    }

    public function rules(): array
    {
        $location = $this->getLocation();
        if (!$location instanceof Location) {
            throw new \Exception('Cannot derive location from route');
        }
        return LocationValidation::make($this)->getUpdateRules($location);
    }

    protected function getLocation()
    {
        /** @var LocationRepositoryInterface $locationRepository */
        $locationRepository = App::make(LocationRepositoryInterface::class);
        return $locationRepository->find($this->route('locationId'));
    }
}
