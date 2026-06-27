<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Values;

use Override;
use Performing\Harmony\Contracts\Value;

final readonly class DropdownValue implements Value
{
    public function __construct(
        private string $value,
        private array $options = [],
    ) {}

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
    public function toContent(): mixed
    {
        return collect($this->options)->firstWhere('value', $this->value);
    }
}
