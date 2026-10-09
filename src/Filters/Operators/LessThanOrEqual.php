<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class LessThanOrEqual extends ComparisonOperator
{
    public function key(): string
    {
        return 'less_than_or_equal';
    }

    public function label(): string
    {
        return __('Less than or equal');
    }

    protected function comparison(): string
    {
        return '<=';
    }
}
