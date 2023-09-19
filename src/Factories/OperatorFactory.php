<?php

namespace Performing\Harmony\Factories;

use Performing\Harmony\Components\Operators\Contains;
use Performing\Harmony\Components\Operators\EndsWith;
use Performing\Harmony\Components\Operators\IsEmpty;
use Performing\Harmony\Components\Operators\IsEqual;
use Performing\Harmony\Components\Operators\IsGreaterThan;
use Performing\Harmony\Components\Operators\IsLessThan;
use Performing\Harmony\Components\Operators\IsNotEmpty;
use Performing\Harmony\Components\Operators\IsNotEqual;
use Performing\Harmony\Components\Operators\IsOneOfAny;
use Performing\Harmony\Components\Operators\NotContains;
use Performing\Harmony\Components\Operators\StartsWith;

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
