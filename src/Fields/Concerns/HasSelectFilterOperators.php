<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNoneOf;
use Performing\Harmony\Filters\Operators\IsNotEmpty;
use Performing\Harmony\Filters\Operators\IsOneOf;
use Performing\Harmony\Filters\Operators\NotEquals;

trait HasSelectFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            new Equals,
            new NotEquals,
            new IsOneOf,
            new IsNoneOf,
            new IsEmpty,
            new IsNotEmpty,
        ];
    }
}
