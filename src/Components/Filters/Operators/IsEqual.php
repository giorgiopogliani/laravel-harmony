<?php

namespace Performing\Harmony\Components\Operators;

class IsEqual extends Operator
{
    public function key(): string
    {
        return 'is_equal';
    }

    public function label(): string
    {
        return __('È uguale a...');
    }

    public function toSql(): string
    {
        return '=';
    }
}
