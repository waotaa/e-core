<?php

namespace Vng\EvaCore\Http\Validation\Rule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Http\Validation\Rule\Traits\DatabaseRule;
use Vng\EvaCore\Repositories\TargetGroupRepositoryInterface;

class SoftDeletedRule implements Rule
{
    use DatabaseRule;

    protected string $attribute;
    protected string $message;
    protected TargetGroupRepositoryInterface $targetGroupRepo;

    protected $ignoreId;
    protected $idColumn = 'id';

    public function __construct(
        string $attribute,
        string $message = 'The :attribute has already been taken by a deactivated record.'
    ) {
        $this->attribute = $attribute;
        $this->message = $message;
        $this->targetGroupRepo = app(TargetGroupRepositoryInterface::class);
    }

    public function ignore($id, $idColumn = null): static
    {
        if ($id instanceof Model) {
            return $this->ignoreModel($id, $idColumn);
        }

        $this->ignoreId = $id;
        $this->idColumn = $idColumn ?? 'id';

        return $this;
    }

    public function ignoreModel($model, $idColumn = null): static
    {
        $this->idColumn = $idColumn ?? $model->getKeyName();
        $this->ignoreId = $model->{$this->idColumn};

        return $this;
    }

    public function passes($attribute, $value)
    {
        $query = $this->targetGroupRepo->builderOnlyTrashed()
            ->where($this->attribute, $value);

        $query = $this->addCustomWheres($query);

        if ($this->ignoreId) {
            $query->where($this->idColumn, '<>', $this->ignoreId);
        }

        $targetGroup = $query->first();

        return !$targetGroup;
    }

    public function message()
    {
        return str_replace(':attribute', $this->attribute, $this->message);
    }
}