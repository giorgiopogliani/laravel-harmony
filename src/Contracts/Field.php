<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface Field
{
    public Identity $identity { get; }

    public Validation $validation { get; }

    public Visibility $visibility { get; }

    public function getValue(): ?Value;

    public function setValue(mixed $value): static;

    /** @return list<string>|null */
    public function toSort(): ?array;
}
