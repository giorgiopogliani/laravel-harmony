<?php

declare(strict_types=1);

use Performing\Harmony\Components\Tables\TableFilter;

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
    $options = ['active' => 'Active', 'inactive' => 'Inactive'];
    $filter = TableFilter::make('Status')->options($options);

    expect($filter->get('options'))->toBe($options);
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

it('can set filters key prefix', function () {
    $filter = TableFilter::make('Status');
    $filter->setFiltersKey('table1');

    expect($filter)->toBeInstanceOf(TableFilter::class);
});
