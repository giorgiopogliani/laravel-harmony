<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use Performing\Harmony\Contracts\FilterOperator;

final class IsEmpty implements FilterOperator
{
    public function key(): string
    {
        return 'is_empty';
    }

    public function label(): string
    {
        return __('Is empty');
    }

    public function requiresValue(): bool
    {
        return false;
    }

    public function multiple(): bool
    {
        return false;
    }

    /** @return array<array-key, mixed> */
    public function options(): array
    {
        return [];
    }

    public function default(): mixed
    {
        return null;
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        return $query->where(static function (Builder $query) use ($column): void {
            $query->whereNull($column)->orWhere($column, '');
        });
    }
}
