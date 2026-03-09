<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

trait IsTextFilterable
{
    public function filterType(): string
    {
        return 'text';
    }
}
