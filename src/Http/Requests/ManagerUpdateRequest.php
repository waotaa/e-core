<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ManagerValidation;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;

class ManagerUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'manager';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getManager());
    }

    public function rules(): array
    {
        $manager = $this->getManager();
        if (!$manager instanceof Manager) {
            throw new \Exception('Cannot derive manager from route');
        }
        return ManagerValidation::make($this)->getUpdateRules($manager);
    }

    protected function getManager()
    {
        /** @var ManagerRepositoryInterface $managerRepository */
        $managerRepository = App::make(ManagerRepositoryInterface::class);
        return $managerRepository->find($this->getModelId());
    }
}
