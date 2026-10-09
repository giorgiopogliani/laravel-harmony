<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;

abstract class ComparisonOperator extends AbstractFilterOperator
{
    abstract protected function comparison(): string;

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        return $query->where($column, $this->comparison(), $value);
    }
}
