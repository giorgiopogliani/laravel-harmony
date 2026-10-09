<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class LessThan extends ComparisonOperator
{
    public function key(): string
    {
        return 'less_than';
    }

    public function label(): string
    {
        return __('Less than');
    }

    protected function comparison(): string
    {
        return '<';
    }
}
