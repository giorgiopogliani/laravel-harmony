<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

final class NotEquals extends ComparisonOperator
{
    public function key(): string
    {
        return 'not_equals';
    }

    public function label(): string
    {
        return __('Does not equal');
    }

    protected function comparison(): string
    {
        return '!=';
    }
}
