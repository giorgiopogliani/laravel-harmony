<?php

namespace Performing\Harmony\Components;

use Override;

class ExternalLink implements Component
{
    public function __construct(
        private Link $component,
    ) {}

    /** @return array{ as: string, target: string, ...} */
    #[Override]
    public function toArray(): array
    {
        return array_merge(['as' => 'a', 'target' => '_blank'], $this->component->toArray());
    }
}
