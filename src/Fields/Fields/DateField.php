<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Fields;

use Override;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\Filterable;
use Performing\Harmony\Contracts\Identity;
use Performing\Harmony\Contracts\Validation;
use Performing\Harmony\Contracts\Value;
use Performing\Harmony\Contracts\Visibility;
use Performing\Harmony\Fields\Concerns\IsDateFilterable;
use Performing\Harmony\Fields\Values\DateValue;

final class DateField implements Field, Filterable
{
    use IsDateFilterable;

    public function __construct(
        public readonly Identity $identity,
        public readonly Validation $validation,
        public readonly Visibility $visibility,
        private ?DateValue $value = null,
    ) {}

    #[Override]
    public function getValue(): ?Value
    {
        return $this->value;
    }

    #[Override]
    public function toSort(): ?array
    {
        return null;
    }

    #[Override]
    public function setValue(mixed $value): static
    {
        $this->value = $value === null ? null : new DateValue($value);

        return $this;
    }
}
