<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class GreaterThan extends ComparisonOperator
{
    public function key(): string
    {
        return 'greater_than';
    }

    public function label(): string
    {
        return __('Greater than');
    }

    protected function comparison(): string
    {
        return '>';
    }
}
