<?php

namespace Performing\Harmony\Components\Filters\Operators;

class IsNotEmpty extends Operator
{
    public function key(): string
    {
        return 'is_not_empty';
    }

    public function standalone(): bool
    {
        return true;
    }

    public function label(): string
    {
        return __('Non è vuoto...');
    }

    public function toSql(): string
    {
        return '<>';
    }
}
