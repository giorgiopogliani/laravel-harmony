<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface FilterableAdvanced
{
    /** @return list<FilterOperator> */
    public function operators(): array;
}
