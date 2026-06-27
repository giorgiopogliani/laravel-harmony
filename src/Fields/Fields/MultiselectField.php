<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Fields;

use Override;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\Filterable;
use Performing\Harmony\Contracts\HasOptions;
use Performing\Harmony\Contracts\Identity;
use Performing\Harmony\Contracts\Validation;
use Performing\Harmony\Contracts\Value;
use Performing\Harmony\Contracts\Visibility;
use Performing\Harmony\Fields\Concerns\IsMultiselectFilterable;
use Performing\Harmony\Fields\Values\MultiselectValue;

final class MultiselectField implements Field, Filterable, HasOptions
{
    use IsMultiselectFilterable;

    public function __construct(
        public readonly Identity $identity,
        public readonly Validation $validation,
        public readonly Visibility $visibility,
        private array $options = [],
        private ?MultiselectValue $value = null,
    ) {}

    #[Override]
    public function getValue(): ?Value
    {
        return $this->value;
    }

    #[Override]
    public function setValue(mixed $value): static
    {
        $values = is_array($value) ? $value : [];
        $this->value = new MultiselectValue($values, $this->options);

        return $this;
    }

    #[Override]
    public function toSort(): ?array
    {
        return collect($this->options)->sortBy('order')->pluck('value')->all();
    }

    #[Override]
    public function getOptions(): array
    {
        return $this->options;
    }
}
