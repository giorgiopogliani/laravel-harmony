<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;

final class Contains extends AbstractFilterOperator
{
    public function key(): string
    {
        return 'contains';
    }

    public function label(): string
    {
        return __('Contains');
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        return $query->where($column, 'like', '%'.(string) $value.'%');
    }
}
