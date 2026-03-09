<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Values;

use Override;
use Performing\Harmony\Contracts\Value;

final readonly class TextValue implements Value
{
    public function __construct(private string $value) {}

    #[Override]
    public function toString(): string
    {
        return $this->value;
    }

    #[Override]
    public function toStorage(): string
    {
        return $this->value;
    }

    #[Override]
    public function toContent(): string
    {
        return $this->value;
    }
}
