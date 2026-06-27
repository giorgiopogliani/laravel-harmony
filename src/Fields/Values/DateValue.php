<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Values;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Exception;
use Override;
use Performing\Harmony\Contracts\Value;

final readonly class DateValue implements Value
{
    private ?string $value;

    public function __construct(mixed $value)
    {
        if (empty($value)) {
            $this->value = null;

            return;
        }

        if ($value instanceof CarbonInterface) {
            $this->value = $value->format('Y-m-d');

            return;
        }

        try {
            $this->value = CarbonImmutable::parse($value)->format('Y-m-d');
        } catch (Exception) {
            $this->value = null;
        }
    }

    #[Override]
    public function toString(): string
    {
        return $this->value ?? '';
    }

    #[Override]
    public function toStorage(): string
    {
        return $this->value ?? '';
    }

    #[Override]
    public function toContent(): ?string
    {
        return $this->value;
    }
}
