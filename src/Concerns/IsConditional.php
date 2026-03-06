<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

trait IsConditional
{
    protected bool $when = true;

    public function when(bool $condition): static
    {
        $this->when = $condition;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->when;
    }
}
