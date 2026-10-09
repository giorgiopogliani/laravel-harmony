<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class IsOneOf implements FilterOperator
{
    public function key(): string
    {
        return 'is_one_of';
    }

    public function label(): string
    {
        return __('Is one of');
    }
}
