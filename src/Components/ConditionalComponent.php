<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Illuminate\Support\Traits\ForwardsCalls;

class ConditionalComponent implements Component
{
    use ForwardsCalls;

    public function __construct(
        protected Component $component,
        protected bool $visible = true,
    ) {}

    #[\Override]
    public function toArray(): array
    {
        return $this->visible ? $this->component->toArray() : [];
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->component, $method, $parameters);
    }
}
