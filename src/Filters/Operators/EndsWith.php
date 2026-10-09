<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class EndsWith implements FilterOperator
{
    public function key(): string
    {
        return 'ends_with';
    }

    public function label(): string
    {
        return __('Ends with');
    }
}
