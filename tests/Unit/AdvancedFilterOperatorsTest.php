<?php

declare(strict_types=1);

use Performing\Harmony\Contracts\Filterable;
use Performing\Harmony\Contracts\FilterableAdvanced;
use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Fields\Concerns\HasDateFilterOperators;
use Performing\Harmony\Fields\Concerns\HasSelectFilterOperators;
use Performing\Harmony\Fields\Concerns\HasTextFilterOperators;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\IsOneOf;

it('keeps advanced filter fields independent of legacy fields', function () {
    expect(is_subclass_of(FilterableAdvanced::class, Filterable::class))->toBeFalse();

    $field = new class implements FilterableAdvanced
    {
        use HasTextFilterOperators;
    };

    expect($field->operators())->toHaveCount(8);

    foreach ($field->operators() as $operator) {
        expect($operator)->toBeInstanceOf(FilterOperator::class);
    }
});

it('keeps operators metadata-only', function () {
    expect(get_class_methods(FilterOperator::class))->toBe(['key', 'label']);

    $operator = new Equals;

    expect($operator->key())->toBe('eq')
        ->and(method_exists($operator, 'apply'))->toBeFalse()
        ->and(method_exists($operator, 'options'))->toBeFalse()
        ->and(method_exists($operator, 'default'))->toBeFalse();
});

it('reuses generic operators with the existing date filter keys', function () {
    $date = new class implements FilterableAdvanced
    {
        use HasDateFilterOperators;
    };

    $select = new class implements FilterableAdvanced
    {
        use HasSelectFilterOperators;
    };

    expect(array_map(static fn (FilterOperator $operator): string => $operator->key(), $date->operators()))
        ->toBe(['eq', 'gte', 'lte', 'is_empty', 'is_not_empty'])
        ->and($date->operators()[0])->toBeInstanceOf(Equals::class)
        ->and($select->operators()[0])->toBeInstanceOf(Equals::class)
        ->and($select->operators()[2])->toBeInstanceOf(IsOneOf::class);
});

it('implements every operator directly with a final class', function () {
    $operators = [
        new \Performing\Harmony\Filters\Operators\Equals,
        new \Performing\Harmony\Filters\Operators\NotEquals,
        new \Performing\Harmony\Filters\Operators\GreaterThan,
        new \Performing\Harmony\Filters\Operators\GreaterThanOrEqual,
        new \Performing\Harmony\Filters\Operators\LessThan,
        new \Performing\Harmony\Filters\Operators\LessThanOrEqual,
        new \Performing\Harmony\Filters\Operators\Contains,
        new \Performing\Harmony\Filters\Operators\NotContains,
        new \Performing\Harmony\Filters\Operators\StartsWith,
        new \Performing\Harmony\Filters\Operators\EndsWith,
        new \Performing\Harmony\Filters\Operators\IsOneOf,
        new \Performing\Harmony\Filters\Operators\IsNoneOf,
        new \Performing\Harmony\Filters\Operators\IsEmpty,
        new \Performing\Harmony\Filters\Operators\IsNotEmpty,
    ];

    foreach ($operators as $operator) {
        $reflection = new ReflectionClass($operator);

        expect($operator)->toBeInstanceOf(FilterOperator::class)
            ->and($reflection->isFinal())->toBeTrue()
            ->and($reflection->getParentClass())->toBeFalse();
    }
});
