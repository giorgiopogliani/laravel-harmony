<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

class ConditionalComponent implements Component
{
    public function __construct(
        protected Component $component,
        protected bool $visible = true,
    ) {}

    #[\Override]
    public function toArray(): array
    {
        return $this->visible ? $this->component->toArray() : [];
    }
}
