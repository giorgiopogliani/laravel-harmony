<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Operators;

final class Contains extends Operator
{
    #[\Override]
    public function key(): string
    {
        return 'contains';
    }

    #[\Override]
    public function label(): string
    {
        return __('Contiene...');
    }

    #[\Override]
    public function toSql(): string
    {
        return 'LIKE';
    }

    #[\Override]
    public function transform($value): ?string
    {
        return '%' . $value . '%';
    }
}
