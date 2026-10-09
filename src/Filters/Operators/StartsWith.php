<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class StartsWith implements FilterOperator
{
    public function key(): string
    {
        return 'starts_with';
    }

    public function label(): string
    {
        return __('Starts with');
    }
}
