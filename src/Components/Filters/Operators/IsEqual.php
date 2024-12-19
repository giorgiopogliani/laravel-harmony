<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

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
