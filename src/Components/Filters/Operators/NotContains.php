<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class NotContains extends Operator
{
    public function key(): string
    {
        return 'not_contains';
    }

    public function label(): string
    {
        return __('Non contiene...');
    }

    public function toSql(): string
    {
        return 'NOT LIKE';
    }

    public function transform($value): ?string
    {
        return '%' . $value . '%';
    }
}
