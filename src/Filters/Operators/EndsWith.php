<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;

final class EndsWith extends AbstractFilterOperator
{
    public function key(): string
    {
        return 'ends_with';
    }

    public function label(): string
    {
        return __('Ends with');
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        return $query->where($column, 'like', '%'.(string) $value.'');
    }
}
