<?php

namespace Performing\Harmony\Criterias;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Performing\Harmony\Components\Filters\Operators\IsEqual;
use Performing\Harmony\Components\Tables\TableFilter;
use Performing\Harmony\Factories\OperatorFactory;

class Criteria
{
    private $value = null;

    private $operator = null;

    private ?Closure $callback = null;

    private ?string $key = null;

    public function key(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function callback(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    public static function from(TableFilter $filter)
    {
        $criteria = new static();

        $params = request()->input('filters.' . $filter->getKey());

        $criteria->key($filter->getKey());

        if ($value = $params['value'] ?? null) {
            if (is_string($value)) {
                $criteria->value($value);
            }
        }

        if ($op = $params['operator'] ?? null) {
            if (is_string($op)) {
                $criteria->operator($op);
            }
        }

        if ($callback = $filter->getCallback()) {
            $criteria->callback($callback);
        }

        return $criteria;
    }

    public function value(string $value)
    {
        $this->value = $value;

        return $this;
    }

    public function operator(string $operator)
    {
        $this->operator = OperatorFactory::getOperator($operator);

        return $this;
    }

    public function hasStandaloneOperator()
    {
        return $this->operator && $this->operator->standalone();
    }

    public function getSqlOperator(): string
    {
        if (!$this->operator) {
            return (new IsEqual())->toSql();
        }

        return $this->operator->toSql();
    }

    public function getSqlValue()
    {
        if ($this->operator) {
            return $this->operator->transform($this->value);
        }

        return $this->value;
    }

    public function apply(Builder $query)
    {
        if ($this->callback) {
            call_user_func(Closure::bind($this->callback, $this), $query, $this->value);
        } else {
            $query->where($this->getKey(), $this->getSqlOperator(), $this->getSqlValue());
        }
    }
}
