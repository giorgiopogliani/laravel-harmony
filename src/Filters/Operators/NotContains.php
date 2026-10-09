<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;

final class NotContains extends AbstractFilterOperator
{
    public function key(): string
    {
        return 'not_contains';
    }

    public function label(): string
    {
        return __('Does not contain');
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        return $query->where($column, 'not like', '%'.(string) $value.'%');
    }
}
