<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class IsNoneOf extends SetOperator
{
    public function key(): string
    {
        return 'is_none_of';
    }

    public function label(): string
    {
        return __('Is none of');
    }

    protected function exclude(): bool
    {
        return true;
    }
}
