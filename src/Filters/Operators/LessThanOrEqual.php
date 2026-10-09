<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use Performing\Harmony\Contracts\FilterOperator;

final readonly class LessThanOrEqual implements FilterOperator
{
    public function key(): string
    {
        return 'less_than_or_equal';
    }

    public function label(): string
    {
        return __('Less than or equal');
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        if ($value === null || $value === '' || $value === []) {
            return $query;
        }

        return $query->where($column, '<=', $value);
    }
}
