<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class GreaterThanOrEqual extends ComparisonOperator
{
    public function key(): string
    {
        return 'greater_than_or_equal';
    }

    public function label(): string
    {
        return __('Greater than or equal');
    }

    protected function comparison(): string
    {
        return '>=';
    }
}
