<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface Contentable
{
    public function setField(Field $field, mixed $value): void;

    public function getLayout(): ?Layoutable;

    public function getFieldValue(Field $field): ?Value;
}
