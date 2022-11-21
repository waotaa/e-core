<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RatingValidation;
use Vng\EvaCore\Models\Rating;
use Vng\EvaCore\Repositories\RatingRepositoryInterface;

class RatingUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getRating());
    }

    public function rules(): array
    {
        $rating = $this->getRating();
        if (!$rating instanceof Rating) {
            throw new \Exception('Cannot derive rating from route');
        }
        return RatingValidation::make($this)->getUpdateRules($rating);
    }

    protected function getRating()
    {
        /** @var RatingRepositoryInterface $ratingRepository */
        $ratingRepository = App::make(RatingRepositoryInterface::class);
        return $ratingRepository->find($this->route('ratingId'));
    }
}
