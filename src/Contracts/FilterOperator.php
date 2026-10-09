<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;

interface FilterOperator
{
    public function key(): string;

    public function label(): string;

    public function requiresValue(): bool;

    /** Whether the operator accepts multiple values. */
    public function multiple(): bool;

    /** @return array<array-key, mixed> */
    public function options(): array;

    public function default(): mixed;

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder;
}
