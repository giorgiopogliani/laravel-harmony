<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Filters\Operators\Contains;
use Performing\Harmony\Filters\Operators\EndsWith;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNotEmpty;
use Performing\Harmony\Filters\Operators\NotContains;
use Performing\Harmony\Filters\Operators\NotEquals;
use Performing\Harmony\Filters\Operators\StartsWith;

trait HasTextFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            new Equals,
            new NotEquals,
            new Contains,
            new NotContains,
            new StartsWith,
            new EndsWith,
            new IsEmpty,
            new IsNotEmpty,
        ];
    }

    public function apply(Builder $query, FilterOperator $operator, mixed $value): Builder
    {
        $column = 'content->'.$this->identity->uuid;

        if ($operator->key() === 'is_empty') {
            return $query->where(static function (Builder $query) use ($column): void {
                $query->whereNull($column)->orWhere($column, '');
            });
        }

        if ($operator->key() === 'is_not_empty') {
            return $query->whereNotNull($column)->where($column, '!=', '');
        }

        if ($value === null || $value === '' || $value === []) {
            return $query;
        }

        return match ($operator->key()) {
            'equals' => $query->where($column, '=', $value),
            'not_equals' => $query->where($column, '!=', $value),
            'contains' => $query->where($column, 'like', '%'.(string) $value.'%'),
            'not_contains' => $query->where($column, 'not like', '%'.(string) $value.'%'),
            'starts_with' => $query->where($column, 'like', (string) $value.'%'),
            'ends_with' => $query->where($column, 'like', '%'.(string) $value),
            default => $query,
        };
    }
}
