<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class IsGreaterThan extends Operator
{
    public function key(): string
    {
        return 'is_greater_than';
    }

    public function label(): string
    {
        return __('È maggiore di...');
    }

    public function toSql(): string
    {
        return '>';
    }
}
