<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class Contains implements FilterOperator
{
    public function key(): string
    {
        return 'contains';
    }

    public function label(): string
    {
        return __('Contains');
    }
}
