<?php

declare(strict_types=1);

namespace Performing\Harmony\Factories;

use Performing\Harmony\Components\Filters\Operators\Contains;
use Performing\Harmony\Components\Filters\Operators\EndsWith;
use Performing\Harmony\Components\Filters\Operators\IsEmpty;
use Performing\Harmony\Components\Filters\Operators\IsEqual;
use Performing\Harmony\Components\Filters\Operators\IsGreaterThan;
use Performing\Harmony\Components\Filters\Operators\IsLessThan;
use Performing\Harmony\Components\Filters\Operators\IsNotEmpty;
use Performing\Harmony\Components\Filters\Operators\IsNotEqual;
use Performing\Harmony\Components\Filters\Operators\IsOneOfAny;
use Performing\Harmony\Components\Filters\Operators\NotContains;
use Performing\Harmony\Components\Filters\Operators\StartsWith;

class OperatorFactory
{
    public static function getOperator(string $name)
    {
        return match ($name) {
            'is_equal' => new IsEqual(),
            'is_not_equal' => new IsNotEqual(),
            'is_greater_than' => new IsGreaterThan(),
            'is_less_than' => new IsLessThan(),
            'starts_with' => new StartsWith(),
            'contains' => new Contains(),
            'not_contains' => new NotContains(),
            'ends_with' => new EndsWith(),
            'is_empty' => new IsEmpty(),
            'is_not_empty' => new IsNotEmpty(),
            'is_one_of_any' => new IsOneOfAny(),
            default => null,
        };
    }
}
