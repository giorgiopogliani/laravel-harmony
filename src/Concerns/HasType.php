<?php

namespace Performing\Harmony\Concerns;

trait HasType
{
    public function bootHasType(): void
    {
        $this->type('text');
    }

    public function type(string $type): self
    {
        $this->data['type'] = $type;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->data['type'] ?? null;
    }
}
