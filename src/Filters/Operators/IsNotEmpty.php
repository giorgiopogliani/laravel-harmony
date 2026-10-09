<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;

final class IsNotEmpty extends AbstractFilterOperator
{
    public function __construct()
    {
        parent::__construct(inputType: null, rules: []);
    }

    public function key(): string
    {
        return 'is_not_empty';
    }

    public function label(): string
    {
        return __('Is not empty');
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        return $query->whereNotNull($column)->where($column, '!=', '');
    }
}
