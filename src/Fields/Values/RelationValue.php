<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Values;

use Override;
use Performing\Harmony\Contracts\Value;

final readonly class RelationValue implements Value
{
    public function __construct(
        private mixed $id,
        private string $displayName = '',
    ) {}

    #[Override]
    public function toString(): string
    {
        return $this->displayName;
    }

    #[Override]
    public function toStorage(): mixed
    {
        return $this->id;
    }

    #[Override]
    public function toContent(): mixed
    {
        return ['value' => $this->id, 'label' => $this->displayName];
    }
}
