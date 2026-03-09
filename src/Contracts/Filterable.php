<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface Filterable
{
    public function filterType(): string;
}
