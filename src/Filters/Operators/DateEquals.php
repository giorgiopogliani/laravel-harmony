<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class DateEquals implements FilterOperator
{
    public function key(): string
    {
        return 'eq';
    }

    public function label(): string
    {
        return __('Equals');
    }
}
