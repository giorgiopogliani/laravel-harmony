<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class IsNoneOf implements FilterOperator
{
    public function key(): string
    {
        return 'is_none_of';
    }

    public function label(): string
    {
        return __('Is none of');
    }
}
