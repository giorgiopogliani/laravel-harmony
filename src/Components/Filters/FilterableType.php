<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters;

use Performing\Harmony\Operators\Operator;

interface FilterableType
{
    public function name(): string;

    public function type(): string;

    public function label(): ?string;

    /** @return array<string, string|array> */
    public function props(): array;

    /** @return Operator[] */
    public function operators(): array;
}
