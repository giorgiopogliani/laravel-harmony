<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class GreaterThan implements FilterOperator
{
    public function key(): string
    {
        return 'greater_than';
    }

    public function label(): string
    {
        return __('Greater than');
    }
}
