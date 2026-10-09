<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Filters\FilterOperator;

interface FilterableAdvanced
{
    /** @return list<FilterOperator> */
    public function operators(): array;

    public function apply(Builder $query, FilterOperator $operator, mixed $value): Builder;
}
