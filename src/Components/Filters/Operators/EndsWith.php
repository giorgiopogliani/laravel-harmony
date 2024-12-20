<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class EndsWith extends Operator
{
    public function key(): string
    {
        return 'ends_with';
    }

    public function label(): string
    {
        return __('Termina con...');
    }

    public function toSql(): string
    {
        return 'LIKE';
    }

    public function transform($value): ?string
    {
        return '%' . $value;
    }
}
