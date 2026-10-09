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
    /** @return array<array-key, mixed> */
    abstract public function getOptions(): array;

    /** @return list<FilterOperator> */
    public function operators(): array
    {
        $options = $this->getOptions();

        return [
            new Equals(options: $options),
            new NotEquals(options: $options),
            new IsOneOf($options),
            new IsNoneOf($options),
            new IsEmpty,
            new IsNotEmpty,
        ];
    }
}
