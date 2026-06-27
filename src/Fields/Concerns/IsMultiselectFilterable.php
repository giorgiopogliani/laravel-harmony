<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

trait IsMultiselectFilterable
{
    public function filterType(): string
    {
        return 'multiselect';
    }
}
