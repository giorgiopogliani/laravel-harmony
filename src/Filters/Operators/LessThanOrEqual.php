<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class LessThanOrEqual implements FilterOperator
{
    public function key(): string
    {
        return 'less_than_or_equal';
    }

    public function label(): string
    {
        return __('Less than or equal');
    }
}
