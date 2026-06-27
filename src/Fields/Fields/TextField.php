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
use Performing\Harmony\Fields\Concerns\IsTextFilterable;
use Performing\Harmony\Fields\Values\TextValue;

final class TextField implements Field, Filterable
{
    use IsTextFilterable;

    public function __construct(
        public readonly Identity $identity,
        public readonly Validation $validation,
        public readonly Visibility $visibility,
        private ?TextValue $value = null,
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
        if ($value === null) {
            $this->value = null;

            return $this;
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->value = new TextValue((string) $value);

        return $this;
    }
}
