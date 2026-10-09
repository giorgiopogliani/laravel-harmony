<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use InvalidArgumentException;
use Performing\Harmony\Filters\FilterOperator;

trait HasSelectFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            FilterOperator::Equals,
            FilterOperator::NotEquals,
            FilterOperator::IsOneOf,
            FilterOperator::IsNoneOf,
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

        if ($operator === FilterOperator::Equals) {
            return $query->where($column, '=', $value);
        }

        if ($operator === FilterOperator::NotEquals) {
            return $query->where($column, '!=', $value);
        }

        if (!in_array($operator, [FilterOperator::IsOneOf, FilterOperator::IsNoneOf], true)) {
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

        if ($value === []) {
            return $query;
        }

        if (in_array($this->identity->type->value(), ['multiselect', 'users'], true)) {
            $uuid = $this->identity->uuid;
            $jsonValues = json_encode(array_map(
                static fn (mixed $item): mixed => is_numeric($item) ? (int) $item : $item,
                $value,
            ));
            $expression = "JSON_OVERLAPS(content->>'$.\"{$uuid}\"', CAST(? AS JSON))";

            return $operator === FilterOperator::IsOneOf
                ? $query->whereRaw($expression, [$jsonValues])
                : $query->whereRaw("NOT {$expression}", [$jsonValues]);
        }

        return $operator === FilterOperator::IsOneOf
            ? $query->whereIn($column, $value)
            : $query->whereNotIn($column, $value);
    }
}
