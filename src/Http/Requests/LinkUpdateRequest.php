<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\LinkValidation;
use Vng\EvaCore\Models\Link;
use Vng\EvaCore\Repositories\LinkRepositoryInterface;

class LinkUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'link';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getLink());
    }

    public function rules(): array
    {
        $link = $this->getLink();
        if (!$link instanceof Link) {
            throw new \Exception('Cannot derive link from route');
        }
        return LinkValidation::make($this)->getUpdateRules($link);
    }

    protected function getLink()
    {
        /** @var LinkRepositoryInterface $linkRepository */
        $linkRepository = App::make(LinkRepositoryInterface::class);
        return $linkRepository->find($this->getModelId());
    }
}
