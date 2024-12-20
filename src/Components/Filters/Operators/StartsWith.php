<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class StartsWith extends Operator
{
    public function key(): string
    {
        return 'starts_with';
    }

    public function label(): string
    {
        return __('Inizia con...');
    }

    public function toSql(): string
    {
        return 'LIKE';
    }

    public function transform($value): ?string
    {
        return $value . '%';
    }
}
