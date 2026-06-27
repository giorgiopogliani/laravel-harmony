<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

trait IsDateFilterable
{
    public function filterType(): string
    {
        return 'date';
    }
}
