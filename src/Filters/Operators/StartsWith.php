<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use Performing\Harmony\Contracts\FilterOperator;

final readonly class StartsWith implements FilterOperator
{
    public function __construct(
        private mixed $defaultValue = null,
    ) {}

    public function key(): string
    {
        return 'starts_with';
    }

    public function label(): string
    {
        return __('Starts with');
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

        return $query->where($column, 'like', (string) $value.'%');
    }
}
