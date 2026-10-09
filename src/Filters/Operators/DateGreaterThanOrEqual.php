<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class DateGreaterThanOrEqual implements FilterOperator
{
    public function key(): string
    {
        return 'gte';
    }

    public function label(): string
    {
        return __('Greater than or equal');
    }
}
