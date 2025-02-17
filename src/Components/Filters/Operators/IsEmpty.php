<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class IsEmpty extends Operator
{
    public function key(): string
    {
        return 'is_empty';
    }

    public function standalone()
    {
        return true;
    }

    public function label(): string
    {
        return __('È vuoto...');
    }

    public function toSql(): string
    {
        return '=';
    }
}
