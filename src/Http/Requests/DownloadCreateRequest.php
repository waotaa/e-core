<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\DownloadValidation;
use Vng\EvaCore\Models\Download;

class DownloadCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Download::class);
    }

    public function rules(): array
    {
        return DownloadValidation::make($this)->getCreationRules();
    }
}
