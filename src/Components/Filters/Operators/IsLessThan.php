<?php

namespace Performing\Harmony\Components\Filters\Operators;

class IsLessThan extends Operator
{
    public function key(): string
    {
        return 'is_less_than';
    }

    public function label(): string
    {
        return __('È minore di...');
    }

    public function toSql(): string
    {
        return '<';
    }
}
