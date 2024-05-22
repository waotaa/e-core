<?php

namespace Vng\EvaCore\Http\Validation\Rule\Traits;

trait DatabaseRule
{
    protected array $wheres = [];

    public function where($column, $value = null): static
    {
        if (is_array($value)) {
            return $this->whereIn($column, $value);
        }

        if (is_null($value)) {
            return $this->whereNull($column);
        }

        $this->wheres[] = [$column, '=', $value];
        return $this;
    }

    public function whereIn($column, array $values): static
    {
        $this->wheres[] = [$column, 'in', $values];

        return $this;
    }

    public function whereNull($column): static
    {
        $this->wheres[] = [$column, 'null'];

        return $this;
    }

    public function addCustomWheres($query)
    {
        foreach ($this->wheres as $where) {
            $query->where($where[0], $where[1], $where[2]);
        }

        return $query;
    }
}
