<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use InvalidArgumentException;

abstract class SetOperator extends AbstractFilterOperator
{
    /** @param array<array-key, mixed> $options */
    public function __construct(array $options = [])
    {
        parent::__construct(inputType: 'multiselect', options: $options, rules: ['required', 'array', 'min:1']);
    }

    abstract protected function exclude(): bool;

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Set operators require an array value.');
        }

        return $this->exclude()
            ? $query->whereNotIn($column, $value)
            : $query->whereIn($column, $value);
    }
}
