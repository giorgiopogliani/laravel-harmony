<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

trait HasKey
{
    public function key(string $key): self
    {
        $this->data['key'] = $key;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->data['key'] ?? null;
    }
}
