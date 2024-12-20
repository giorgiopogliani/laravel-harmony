<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters;

use Illuminate\Database\Eloquent\Builder;
use Performing\Harmony\Factories\OperatorFactory;
use Performing\Harmony\Components\Operators\IsEqual;

trait FilterScope
{
    private $value = null;

    private $operator = null;

    public function withValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    public function withOperator(string $operator)
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
        if (! $this->operator) {
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

    abstract public function apply(Builder $query): void;
}
