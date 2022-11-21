<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\DownloadValidation;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Repositories\DownloadRepositoryInterface;

class DownloadUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getDownload());
    }

    public function rules(): array
    {
        $download = $this->getDownload();
        if (!$download instanceof Download) {
            throw new \Exception('Cannot derive download from route');
        }
        return DownloadValidation::make($this)->getUpdateRules($download);
    }

    protected function getDownload()
    {
        /** @var DownloadRepositoryInterface $downloadRepository */
        $downloadRepository = App::make(DownloadRepositoryInterface::class);
        return $downloadRepository->find($this->route('downloadId'));
    }
}
