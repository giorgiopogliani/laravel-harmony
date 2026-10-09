<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use Performing\Harmony\Contracts\FilterOperator;
use InvalidArgumentException;

final readonly class IsNoneOf implements FilterOperator
{
    public function __construct(
        private mixed $defaultValue = null,
    ) {}

    public function key(): string
    {
        return 'is_none_of';
    }

    public function label(): string
    {
        return __('Is none of');
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

        if (is_string($value)) {
            if (str_starts_with($value, '[')) {
                $decoded = json_decode($value, true);

                if (!is_array($decoded) || !array_is_list($decoded)) {
                    return $query;
                }

                $value = $decoded;
            } else {
                $value = explode(',', $value);
            }
        }

        if (!is_array($value)) {
            throw new InvalidArgumentException('Set operators require a list or comma-separated string.');
        }

        return $query->whereNotIn($column, $value);
    }
}
