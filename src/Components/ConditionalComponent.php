<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

final class ConditionalComponent extends Component
{
    public function __construct(
        protected Component $component,
        protected bool $visible = true,
    ) {}

    public function toArray(): array
    {
        return $this->visible ? $this->component->toArray() : [];
    }
}
