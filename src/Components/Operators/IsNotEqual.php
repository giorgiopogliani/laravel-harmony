<?php

namespace Performing\Harmony\Components\Operators;

class IsNotEqual extends Operator
{
    public function key(): string
    {
        return 'is_not_equal';
    }

    public function label(): string
    {
        return __('Non è uguale a...');
    }

    public function toSql(): string
    {
        return '<>';
    }
}
