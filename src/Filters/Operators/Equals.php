<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Performing\Harmony\Contracts\FilterOperator;

final readonly class Equals implements FilterOperator
{
    public function key(): string
    {
        return 'equals';
    }

    public function label(): string
    {
        return __('Equals');
    }
}
