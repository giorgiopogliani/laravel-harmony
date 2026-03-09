<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class IsEmpty extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'is_empty';
    }

    #[\Override]
    public function standalone()
    {
        return true;
    }

    #[\Override]
    public function label(): string
    {
        return __('È vuoto...');
    }

    #[\Override]
    public function toSql(): string
    {
        return '=';
    }
}
