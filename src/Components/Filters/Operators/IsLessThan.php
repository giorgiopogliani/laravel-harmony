<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class IsLessThan extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'is_less_than';
    }

    #[\Override]
    public function label(): string
    {
        return __('È minore di...');
    }

    #[\Override]
    public function toSql(): string
    {
        return '<';
    }
}
