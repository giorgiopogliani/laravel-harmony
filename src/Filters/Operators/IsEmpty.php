<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;

final class IsEmpty extends AbstractFilterOperator
{
    public function __construct()
    {
        parent::__construct(inputType: null, rules: []);
    }

    public function key(): string
    {
        return 'is_empty';
    }

    public function label(): string
    {
        return __('Is empty');
    }

    public function apply(Builder $query, string|Expression $column, mixed $value): Builder
    {
        return $query->where(static function (Builder $query) use ($column): void {
            $query->whereNull($column)->orWhere($column, '');
        });
    }
}
