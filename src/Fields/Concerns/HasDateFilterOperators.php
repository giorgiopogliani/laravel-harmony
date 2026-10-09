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
            new Equals(inputType: 'date', rules: ['required', 'date']),
            new GreaterThan(inputType: 'date', rules: ['required', 'date']),
            new GreaterThanOrEqual(inputType: 'date', rules: ['required', 'date']),
            new LessThan(inputType: 'date', rules: ['required', 'date']),
            new LessThanOrEqual(inputType: 'date', rules: ['required', 'date']),
            new IsEmpty,
            new IsNotEmpty,
        ];
    }
}
