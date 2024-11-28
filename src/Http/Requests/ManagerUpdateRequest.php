<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ManagerValidation;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

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

    protected function getManager(): Model|Manager|null
    {
        $managerId = $this->getRouteIdParameter($this->modelName);

        /** @var ManagerRepositoryInterface $managerRepository */
        if (!is_null($managerId)) {
            $managerRepository = App::make(ManagerRepositoryInterface::class);
            return $managerRepository->find($managerId);
        }

        $userId = $this->getRouteIdParameter('user');
        if (!is_null($userId)) {
            /** @var UserRepositoryInterface $userRepository */
            $userRepository = App::make(UserRepositoryInterface::class);
            /** @var IsManagerInterface $user */
            $user = $userRepository->find($userId);
            return $user->getManager();
        }

        return $managerRepository->find($this->getRouteFirstParameter());
    }
}
