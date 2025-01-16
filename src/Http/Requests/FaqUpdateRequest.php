<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\FaqValidation;
use Vng\EvaCore\Models\Faq;
use Vng\EvaCore\Repositories\FaqRepositoryInterface;

class FaqUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'faq';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getFaq());
    }

    public function rules(): array
    {
        $faq = $this->getFaq();
        if (!$faq instanceof Faq) {
            throw new \Exception('Cannot derive FAQ from route');
        }

        return FaqValidation::make($this)->getUpdateRules($faq);
    }

    protected function getFaq()
    {
        /** @var FaqRepositoryInterface $faqRepository */
        $faqRepository = App::make(FaqRepositoryInterface::class);
        return $faqRepository->find($this->getModelId());
    }
}

