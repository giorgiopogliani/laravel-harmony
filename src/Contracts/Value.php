<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface Value
{
    public function toString(): string;

    public function toStorage(): mixed;

    public function toContent(): mixed;
}
