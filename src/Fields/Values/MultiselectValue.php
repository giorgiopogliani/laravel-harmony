<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Values;

use Override;
use Performing\Harmony\Contracts\Value;

final readonly class MultiselectValue implements Value
{
    public function __construct(
        private array $values,
        private array $options = [],
    ) {}

    #[Override]
    public function toString(): string
    {
        return collect($this->options)
            ->filter(fn (array $option) => in_array($option['value'], $this->values))
            ->pluck('label')
            ->join(', ');
    }

    #[Override]
    public function toStorage(): array
    {
        return $this->values;
    }

    #[Override]
    public function toContent(): array
    {
        return collect($this->options)
            ->filter(fn (array $option) => in_array($option['value'], $this->values))
            ->values()
            ->all();
    }
}
