<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class Equals extends ComparisonOperator
{
    public function key(): string
    {
        return 'equals';
    }

    public function label(): string
    {
        return __('Equals');
    }

    protected function comparison(): string
    {
        return '=';
    }
}
