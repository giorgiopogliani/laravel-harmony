<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class StartsWith extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'starts_with';
    }

    #[\Override]
    public function label(): string
    {
        return __('Inizia con...');
    }

    #[\Override]
    public function toSql(): string
    {
        return 'LIKE';
    }

    #[\Override]
    public function transform($value): ?string
    {
        return $value . '%';
    }
}
