<?php

declare(strict_types=1);

use Performing\Harmony\Components\Tables\TableColumn;

it('can be created with make', function () {
    $column = TableColumn::make('Title');

    expect($column->getKey())->toBe('title')
        ->and($column->toArray())->toHaveKey('title', 'Title');
});

it('generates key from title using snake case', function () {
    $column = TableColumn::make('Created At');

    expect($column->getKey())->toBe('created_at');
});

it('accepts a custom key', function () {
    $column = TableColumn::make('Name', 'user_name');

    expect($column->getKey())->toBe('user_name');
});

it('defaults type to text', function () {
    $column = TableColumn::make('Title');

    expect($column->getType())->toBe('text');
});

it('can set type', function () {
    $column = TableColumn::make('Title')->type('number');

    expect($column->getType())->toBe('number');
});

it('can be marked as sortable', function () {
    $column = TableColumn::make('Title')->sortable();

    expect($column->toArray())->toHaveKey('sortable', true);
});

it('can be marked as hidden', function () {
    $column = TableColumn::make('ID')->hidden();

    expect($column->toArray())->toHaveKey('hidden', true);
});

it('can set a format closure', function () {
    $column = TableColumn::make('Title')
        ->format(fn ($item, $col) => strtoupper($item->title));

    expect($column->format)->toBeInstanceOf(Closure::class);
});

it('can set a groupAs closure', function () {
    $column = TableColumn::make('Category')
        ->groupAs(fn ($item) => $item->category);

    expect($column->groupAs)->toBeInstanceOf(Closure::class);
});
