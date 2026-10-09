<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class GreaterThanOrEqual implements FilterOperator
{
    public function key(): string
    {
        return 'greater_than_or_equal';
    }

    public function label(): string
    {
        return __('Greater than or equal');
    }
}
