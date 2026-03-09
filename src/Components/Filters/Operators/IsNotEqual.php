<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class IsNotEqual extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'is_not_equal';
    }

    #[\Override]
    public function label(): string
    {
        return __('Non è uguale a...');
    }

    #[\Override]
    public function toSql(): string
    {
        return '<>';
    }
}
