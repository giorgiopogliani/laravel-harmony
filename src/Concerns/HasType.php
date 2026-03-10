<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

use Performing\Harmony\Prop;

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

    #[Prop('type')]
    public function getType(): ?string
    {
        return $this->data['type'] ?? null;
    }
}
