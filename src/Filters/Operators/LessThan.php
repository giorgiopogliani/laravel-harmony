<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class LessThan implements FilterOperator
{
    public function key(): string
    {
        return 'less_than';
    }

    public function label(): string
    {
        return __('Less than');
    }
}
