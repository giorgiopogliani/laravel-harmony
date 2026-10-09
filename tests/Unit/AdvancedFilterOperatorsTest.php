<?php

declare(strict_types=1);

use Performing\Harmony\Contracts\Filterable;
use Performing\Harmony\Contracts\FilterableAdvanced;
use Performing\Harmony\Fields\Concerns\HasDateFilterOperators;
use Performing\Harmony\Fields\Concerns\HasSelectFilterOperators;
use Performing\Harmony\Fields\Concerns\HasTextFilterOperators;
use Performing\Harmony\Filters\FilterOperator;

uses(Tests\TestCase::class);

it('keeps advanced filter fields independent of legacy fields', function () {
    expect(is_subclass_of(FilterableAdvanced::class, Filterable::class))->toBeFalse();

    $field = new class implements FilterableAdvanced {
        use HasTextFilterOperators;
    };

    expect($field->operators())->toHaveCount(8)
        ->and($field->operators()[0])->toBe(FilterOperator::Equals);
});

it('resolves operators using backed-enum tryFrom', function () {
    expect(FilterOperator::tryFrom('eq'))->toBe(FilterOperator::Equals)
        ->and(FilterOperator::tryFrom('gte'))->toBe(FilterOperator::GreaterThanOrEqual)
        ->and(FilterOperator::tryFrom('less_than'))->toBe(FilterOperator::LessThan)
        ->and(FilterOperator::tryFrom('is_one_of'))->toBe(FilterOperator::IsOneOf)
        ->and(FilterOperator::tryFrom('invalid'))->toBeNull()
        ->and(FilterOperator::tryFrom(''))->toBeNull();
});

it('keeps existing wire keys and labels', function () {
    expect(array_map(static fn (FilterOperator $operator): string => $operator->value, FilterOperator::cases()))
        ->toBe([
            'eq', 'not_equals', 'greater_than', 'gte', 'less_than', 'lte',
            'contains', 'not_contains', 'starts_with', 'ends_with',
            'is_one_of', 'is_none_of', 'is_empty', 'is_not_empty',
        ])
        ->and(FilterOperator::Equals->label())->toBe(__('Equals'))
        ->and(FilterOperator::GreaterThan->label())->toBe(__('Greater than'))
        ->and(FilterOperator::IsEmpty->label())->toBe(__('Is empty'));
});

it('uses the same operator enum cases for date and selection fields', function () {
    $date = new class implements FilterableAdvanced {
        use HasDateFilterOperators;
    };

    $select = new class implements FilterableAdvanced {
        use HasSelectFilterOperators;
    };

    expect($date->operators())->toBe([
        FilterOperator::Equals,
        FilterOperator::GreaterThan,
        FilterOperator::GreaterThanOrEqual,
        FilterOperator::LessThan,
        FilterOperator::LessThanOrEqual,
        FilterOperator::IsEmpty,
        FilterOperator::IsNotEmpty,
    ])
        ->and($select->operators())->toBe([
            FilterOperator::Equals,
            FilterOperator::NotEquals,
            FilterOperator::IsOneOf,
            FilterOperator::IsNoneOf,
            FilterOperator::IsEmpty,
            FilterOperator::IsNotEmpty,
        ]);
});
