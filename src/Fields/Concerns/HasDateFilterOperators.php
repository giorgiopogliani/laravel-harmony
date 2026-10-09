<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\GreaterThan;
use Performing\Harmony\Filters\Operators\GreaterThanOrEqual;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNotEmpty;
use Performing\Harmony\Filters\Operators\LessThan;
use Performing\Harmony\Filters\Operators\LessThanOrEqual;

trait HasDateFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            new Equals,
            new GreaterThan,
            new GreaterThanOrEqual,
            new LessThan,
            new LessThanOrEqual,
            new IsEmpty,
            new IsNotEmpty,
        ];
    }
}
