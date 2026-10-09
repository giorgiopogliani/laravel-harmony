<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class NotEquals implements FilterOperator
{
    public function key(): string
    {
        return 'not_equals';
    }

    public function label(): string
    {
        return __('Does not equal');
    }
}
