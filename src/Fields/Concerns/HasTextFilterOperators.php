<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Filters\Operators\Contains;
use Performing\Harmony\Filters\Operators\EndsWith;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNotEmpty;
use Performing\Harmony\Filters\Operators\NotContains;
use Performing\Harmony\Filters\Operators\NotEquals;
use Performing\Harmony\Filters\Operators\StartsWith;

trait HasTextFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            new Equals,
            new NotEquals,
            new Contains,
            new NotContains,
            new StartsWith,
            new EndsWith,
            new IsEmpty,
            new IsNotEmpty,
        ];
    }
}
