<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

class Contains extends Operator
{
    public function key(): string
    {
        return 'contains';
    }

    public function label(): string
    {
        return __('Contiene...');
    }

    public function toSql(): string
    {
        return 'LIKE';
    }

    public function transform($value): ?string
    {
        return '%' . $value . '%';
    }
}
