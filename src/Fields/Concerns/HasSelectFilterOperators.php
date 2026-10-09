<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use InvalidArgumentException;
use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNoneOf;
use Performing\Harmony\Filters\Operators\IsNotEmpty;
use Performing\Harmony\Filters\Operators\IsOneOf;
use Performing\Harmony\Filters\Operators\NotEquals;

trait HasSelectFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            new Equals,
            new NotEquals,
            new IsOneOf,
            new IsNoneOf,
            new IsEmpty,
            new IsNotEmpty,
        ];
    }

    public function apply(Builder $query, FilterOperator $operator, mixed $value): Builder
    {
        $column = 'content->'.$this->identity->uuid;
        $key = $operator->key();

        if ($key === 'is_empty') {
            return $query->where(static function (Builder $query) use ($column): void {
                $query->whereNull($column)->orWhere($column, '');
            });
        }

        if ($key === 'is_not_empty') {
            return $query->whereNotNull($column)->where($column, '!=', '');
        }

        if ($value === null || $value === '' || $value === []) {
            return $query;
        }

        if ($key === 'eq') {
            return $query->where($column, '=', $value);
        }

        if ($key === 'not_equals') {
            return $query->where($column, '!=', $value);
        }

        if (!in_array($key, ['is_one_of', 'is_none_of'], true)) {
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

            return $key === 'is_one_of'
                ? $query->whereRaw($expression, [$jsonValues])
                : $query->whereRaw("NOT {$expression}", [$jsonValues]);
        }

        return $key === 'is_one_of'
            ? $query->whereIn($column, $value)
            : $query->whereNotIn($column, $value);
    }
}
