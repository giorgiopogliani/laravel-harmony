<?php

declare(strict_types=1);

use Performing\Harmony\Columns\BoolColumn;
use Performing\Harmony\Columns\DateColumn;
use Performing\Harmony\Columns\LinkColumn;
use Performing\Harmony\Columns\TextColumn;
use Performing\Harmony\Contracts\Linkable;
use Performing\Harmony\Contracts\Record;

function fakeRecord(object $model): Record
{
    return new class ($model) implements Record {
        public function __construct(private readonly object $model) {}

        public function model(): mixed
        {
            return $this->model;
        }
    };
}

it('text column extracts value from record', function () {
    $column = new TextColumn(base: Record::class, name: 'Name', key: 'name');
    $record = fakeRecord((object) ['name' => 'John']);

    expect($column->value($record))->toBe('John');
});

it('text column generates key from name', function () {
    $column = new TextColumn(base: Record::class, name: 'Full Name');

    expect($column->key())->toBe('full-name');
});

it('text column uses custom key', function () {
    $column = new TextColumn(base: Record::class, name: 'Name', key: 'custom_key');

    expect($column->key())->toBe('custom_key');
});

it('text column serializes to array', function () {
    $column = new TextColumn(base: Record::class, name: 'Name', key: 'name');

    expect($column->jsonSerialize())->toBe([
        'key' => 'name',
        'title' => 'Name',
        'type' => 'text',
    ]);
});

it('bool column extracts value from record', function () {
    $column = new BoolColumn(base: Record::class, name: 'Active', key: 'active');
    $record = fakeRecord((object) ['active' => true]);

    expect($column->value($record))->toBeTrue();
});

it('bool column serializes to array', function () {
    $column = new BoolColumn(base: Record::class, name: 'Active', key: 'active');

    expect($column->jsonSerialize())->toBe([
        'key' => 'active',
        'title' => 'Active',
        'type' => 'bool',
    ]);
});

it('date column formats carbon dates', function () {
    $column = new DateColumn(base: Record::class, name: 'Created', key: 'created_at', format: 'd M Y');
    $record = fakeRecord((object) ['created_at' => now()->parse('2026-01-15')]);

    expect($column->value($record))->toBe('15 Jan 2026');
});

it('date column returns raw value for non-carbon values', function () {
    $column = new DateColumn(base: Record::class, name: 'Created', key: 'created_at');
    $record = fakeRecord((object) ['created_at' => '2026-01-15']);

    expect($column->value($record))->toBe('2026-01-15');
});

it('link column returns name and href', function () {
    $model = new class implements Linkable {
        public string $name = 'Project A';

        public function url(): string
        {
            return '/projects/1';
        }
    };

    $column = new LinkColumn(base: $model::class, name: 'Name', key: 'name');
    $record = fakeRecord($model);

    expect($column->value($record))->toBe([
        'name' => 'Project A',
        'href' => '/projects/1',
    ]);
});

it('text column resolves nested keys from record', function () {
    $column = new TextColumn(base: Record::class, name: 'Client', key: 'client.name');
    $record = fakeRecord((object) ['client' => (object) ['name' => 'Acme']]);

    expect($column->value($record))->toBe('Acme');
});
