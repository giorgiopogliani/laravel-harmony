<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final class IsEmpty implements FilterOperator
{
    public function key(): string
    {
        return 'is_empty';
    }

    public function label(): string
    {
        return __('Is empty');
    }
}
