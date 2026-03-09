<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class EndsWith extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'ends_with';
    }

    #[\Override]
    public function label(): string
    {
        return __('Termina con...');
    }

    #[\Override]
    public function toSql(): string
    {
        return 'LIKE';
    }

    #[\Override]
    public function transform($value): ?string
    {
        return '%' . $value;
    }
}
