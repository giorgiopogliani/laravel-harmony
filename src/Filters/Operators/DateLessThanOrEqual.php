<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class DateLessThanOrEqual implements FilterOperator
{
    public function key(): string
    {
        return 'lte';
    }

    public function label(): string
    {
        return __('Less than or equal');
    }
}
