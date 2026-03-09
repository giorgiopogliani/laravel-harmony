<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

final class IsNotEmpty extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'is_not_empty';
    }

    #[\Override]
    public function standalone(): bool
    {
        return true;
    }

    #[\Override]
    public function label(): string
    {
        return __('Non è vuoto...');
    }

    #[\Override]
    public function toSql(): string
    {
        return '<>';
    }
}
