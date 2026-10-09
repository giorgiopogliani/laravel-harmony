<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use Performing\Harmony\Contracts\FilterOperator;

final readonly class Contains implements FilterOperator
{
    public function __construct(
        private mixed $defaultValue = null,
    ) {}

    public function key(): string
    {
        return 'contains';
    }

    public function label(): string
    {
        return __('Contains');
    }

    public function default(): mixed
    {
        return $this->defaultValue;
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        if ($value === null || $value === '' || $value === []) {
            return $query;
        }

        return $query->where($column, 'like', '%'.(string) $value.'%');
    }
}
