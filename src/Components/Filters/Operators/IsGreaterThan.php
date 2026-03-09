<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

final class IsGreaterThan extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'is_greater_than';
    }

    #[\Override]
    public function label(): string
    {
        return __('È maggiore di...');
    }

    #[\Override]
    public function toSql(): string
    {
        return '>';
    }
}
