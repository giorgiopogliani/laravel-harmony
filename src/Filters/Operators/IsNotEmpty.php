<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final class IsNotEmpty implements FilterOperator
{
    public function key(): string
    {
        return 'is_not_empty';
    }

    public function label(): string
    {
        return __('Is not empty');
    }
}
