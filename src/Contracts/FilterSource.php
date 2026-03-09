<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface FilterSource
{
    public function get(string $key): ?string;
}
