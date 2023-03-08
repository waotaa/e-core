<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\VideoValidation;
use Vng\EvaCore\Models\Video;

class VideoCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Video::class);
    }

    public function rules(): array
    {
        return VideoValidation::make($this)->getCreationRules();
    }
}
