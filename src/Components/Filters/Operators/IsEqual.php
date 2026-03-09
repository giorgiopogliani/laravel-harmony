<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

final class IsEqual extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'is_equal';
    }

    #[\Override]
    public function label(): string
    {
        return __('È uguale a...');
    }

    #[\Override]
    public function toSql(): string
    {
        return '=';
    }
}
