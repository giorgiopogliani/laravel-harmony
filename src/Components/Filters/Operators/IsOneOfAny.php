<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class IsOneOfAny extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'is_one_of_any';
    }

    #[\Override]
    public function label(): string
    {
        return __('È uno tra...');
    }

    #[\Override]
    public function toSql(): string
    {
        return 'in';
    }
}
