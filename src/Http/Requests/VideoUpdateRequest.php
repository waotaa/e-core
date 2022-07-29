<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\VideoValidation;
use Vng\EvaCore\Models\Video;

class VideoUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('video'));
    }

    public function rules(): array
    {
        $video = $this->route('video');
        if (!$video instanceof Video) {
            throw new \Exception('Cannot derive video from route');
        }
        return VideoValidation::getUpdateRules($video);
    }
}
