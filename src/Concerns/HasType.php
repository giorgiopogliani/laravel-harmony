<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

use Performing\Harmony\Prop;

/** @deprecated */
trait HasType
{
    public function bootHasType(): void
    {
        $this->setType('text');
    }

    public function setType(string $type): self
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
