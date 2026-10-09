<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface FilterOperator
{
    public function key(): string;

    public function label(): string;
}
