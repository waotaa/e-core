<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\PartnershipValidation;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Repositories\PartnershipRepositoryInterface;

class PartnershipUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getPartnership());
    }

    public function rules(): array
    {
        $partnership = $this->getPartnership();
        if (!$partnership instanceof Partnership) {
            throw new \Exception('Cannot derive partnership from route');
        }
        return PartnershipValidation::make($this)->getUpdateRules($partnership);
    }

    protected function getPartnership()
    {
        /** @var PartnershipRepositoryInterface $partnershipRepository */
        $partnershipRepository = App::make(PartnershipRepositoryInterface::class);
        return $partnershipRepository->find($this->route('partnershipId'));
    }
}
