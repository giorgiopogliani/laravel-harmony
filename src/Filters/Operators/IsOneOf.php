<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class IsOneOf extends SetOperator
{
    public function key(): string
    {
        return 'is_one_of';
    }

    public function label(): string
    {
        return __('Is one of');
    }

    protected function exclude(): bool
    {
        return false;
    }
}
