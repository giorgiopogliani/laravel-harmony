<?php

declare(strict_types=1);

use Performing\Harmony\Components\Tables\TableFilter;
use Performing\Harmony\Contracts\FilterSource;
use Performing\Harmony\Filters\SelectFilter;

it('can be created with make', function () {
    $filter = TableFilter::make('Status');

    expect($filter->getKey())->toBe('status');
});

it('generates key from title', function () {
    $filter = TableFilter::make('Created At');

    expect($filter->getKey())->toBe('created_at');
});

it('accepts a custom key', function () {
    $filter = TableFilter::make('Status', 'filter_status');

    expect($filter->getKey())->toBe('filter_status');
});

it('defaults type to text', function () {
    $filter = TableFilter::make('Search');

    expect($filter->getType())->toBe('text');
});

it('can set type', function () {
    $filter = TableFilter::make('Status')->type('select');

    expect($filter->getType())->toBe('select');
});

it('can set options', function () {
    $options = [
        ['label' => 'Active', 'value' => 'active'],
        ['label' => 'Inactive', 'value' => 'inactive'],
    ];
    $filter = TableFilter::make('Status')->withOptions($options);

    expect($filter->options())->toBe($options)->and($filter->get('options'))->toBe($options);
});

it('declares select filter options', function () {
    $source = new class implements FilterSource {
        public function get(string $key): ?string
        {
            return null;
        }
    };
    $options = [
        ['label' => 'Active', 'value' => 'active'],
        ['label' => 'Inactive', 'value' => 'inactive'],
    ];
    $filter = new SelectFilter($source, 'status', 'Status', $options);

    expect($filter->options())->toBe($options)->and($filter->jsonSerialize()['options'])->toBe($options);
});

it('can set a default value', function () {
    $filter = TableFilter::make('Status')->default('active');

    expect($filter)->toBeInstanceOf(TableFilter::class);
});

it('can set a query closure', function () {
    $filter = TableFilter::make('Status')
        ->query(fn ($query, $value) => $query->where('status', $value));

    expect($filter)->toBeInstanceOf(TableFilter::class);
});

it('can resolve value and active state', function () {
    $filter = TableFilter::make('Status');
    $filter->resolve('active', true);

    $array = $filter->toArray();

    expect($array['value'])->toBe('active')->and($array['active'])->toBeTrue();
});
