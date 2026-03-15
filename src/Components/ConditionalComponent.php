<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Illuminate\Support\Traits\ForwardsCalls;

final class ConditionalComponent extends Component
{
    use ForwardsCalls;

    public function __construct(
        protected Component $component,
        protected bool $visible = true,
    ) {}

    public function toArray(): array
    {
        return $this->visible ? $this->component->toArray() : [];
    }

    public function __call($method, $arguments)
    {
        return $this->forwardCallTo($this->component, $method, $arguments);
    }
}
