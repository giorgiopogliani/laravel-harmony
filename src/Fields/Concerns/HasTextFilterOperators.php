<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Filters\FilterOperator;

trait HasTextFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            FilterOperator::Equals,
            FilterOperator::NotEquals,
            FilterOperator::Contains,
            FilterOperator::NotContains,
            FilterOperator::StartsWith,
            FilterOperator::EndsWith,
            FilterOperator::IsEmpty,
            FilterOperator::IsNotEmpty,
        ];
    }

    public function apply(Builder $query, FilterOperator $operator, mixed $value): Builder
    {
        $column = 'content->'.$this->identity->uuid;

        if ($operator === FilterOperator::IsEmpty) {
            return $query->where(static function (Builder $query) use ($column): void {
                $query->whereNull($column)->orWhere($column, '');
            });
        }

        if ($operator === FilterOperator::IsNotEmpty) {
            return $query->whereNotNull($column)->where($column, '!=', '');
        }

        if ($value === null || $value === '' || $value === []) {
            return $query;
        }

        return match ($operator) {
            FilterOperator::Equals => $query->where($column, '=', $value),
            FilterOperator::NotEquals => $query->where($column, '!=', $value),
            FilterOperator::Contains => $query->where($column, 'like', '%'.(string) $value.'%'),
            FilterOperator::NotContains => $query->where($column, 'not like', '%'.(string) $value.'%'),
            FilterOperator::StartsWith => $query->where($column, 'like', (string) $value.'%'),
            FilterOperator::EndsWith => $query->where($column, 'like', '%'.(string) $value),
            default => $query,
        };
    }
}
