<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class NotContains extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'not_contains';
    }

    #[\Override]
    public function label(): string
    {
        return __('Non contiene...');
    }

    #[\Override]
    public function toSql(): string
    {
        return 'NOT LIKE';
    }

    #[\Override]
    public function transform($value): ?string
    {
        return '%' . $value . '%';
    }
}
