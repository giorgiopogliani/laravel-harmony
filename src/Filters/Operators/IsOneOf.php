<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use Performing\Harmony\Contracts\FilterOperator;
use InvalidArgumentException;

final readonly class IsOneOf implements FilterOperator
{
    /** @param array<array-key, mixed> $options */
    public function __construct(
        private array $options = [],
        private mixed $defaultValue = null,
    ) {}

    public function key(): string
    {
        return 'is_one_of';
    }

    public function label(): string
    {
        return __('Is one of');
    }

    public function requiresValue(): bool
    {
        return true;
    }

    public function multiple(): bool
    {
        return true;
    }

    /** @return array<array-key, mixed> */
    public function options(): array
    {
        return $this->options;
    }

    public function default(): mixed
    {
        return $this->defaultValue;
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Set operators require an array value.');
        }

        return $query->whereIn($column, $value);
    }
}
