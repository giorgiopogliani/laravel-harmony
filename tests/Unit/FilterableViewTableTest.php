<?php

declare(strict_types=1);

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Performing\Harmony\Columns\TextColumn;
use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Tables\FilterableViewTable;
use Performing\Harmony\Tables\ViewTable;

uses(Tests\TestCase::class, RefreshDatabase::class);

class FilterFakeProject extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'filter_fake_projects';

    protected $guarded = [];
}

class FakeFilter implements Filter
{
    public function __construct(
        private readonly string $column,
        private readonly mixed $value,
    ) {}

    public function key(): string
    {
        return $this->column;
    }

    public function label(): string
    {
        return $this->column;
    }

    public function type(): string
    {
        return 'text';
    }

    public function inline(): bool
    {
        return false;
    }

    public function apply(Builder $query): Builder
    {
        return $query->where($this->column, $this->value);
    }

    public function jsonSerialize(): array
    {
        return ['key' => $this->column, 'value' => $this->value];
    }
}

beforeEach(function () {
    Schema::create('filter_fake_projects', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('code');
        $table->timestamps();
    });
});

it('delegates columns to inner table', function () {
    $view = new FakeView();
    $table = new ViewTable($view, FilterFakeProject::query());
    $table->add(new TextColumn(base: FilterFakeProject::class, name: 'Name', key: 'name'));

    $filtered = new FilterableViewTable($table, FilterFakeProject::query());

    expect($filtered->columns())->toHaveCount(1)
        ->and($filtered->columns()[0]->key())->toBe('name');
});

it('merges filters into additional', function () {
    $view = new FakeView();
    $table = new ViewTable($view, FilterFakeProject::query());
    $table->add(new TextColumn(base: FilterFakeProject::class, name: 'Name', key: 'name'));

    $filtered = new FilterableViewTable($table, FilterFakeProject::query());
    $filtered->add(new FakeFilter('name', 'Alpha'));

    $additional = $filtered->additional();

    expect($additional)->toHaveKey('filters')
        ->and($additional['filters'])->toHaveCount(1)
        ->and($additional)->toHaveKey('columns');
});

it('applies filters to query on render', function () {
    FilterFakeProject::create(['name' => 'Alpha', 'code' => 'A']);
    FilterFakeProject::create(['name' => 'Beta', 'code' => 'B']);
    FilterFakeProject::create(['name' => 'Alpha', 'code' => 'C']);

    $view = new FakeView();
    $table = new ViewTable($view, FilterFakeProject::query());
    $table->add(new TextColumn(base: FilterFakeProject::class, name: 'Name', key: 'name'));
    $table->add(new TextColumn(base: FilterFakeProject::class, name: 'Code', key: 'code'));

    $filtered = new FilterableViewTable($table, FilterFakeProject::query());
    $filtered->add(new FakeFilter('name', 'Alpha'));

    $result = $filtered->render();
    $data = $result->resolve();

    expect($data)->toHaveCount(2)
        ->and($data[0]['name'])->toBe('Alpha')
        ->and($data[1]['name'])->toBe('Alpha');
});

it('returns all rows when no filters added', function () {
    FilterFakeProject::create(['name' => 'Alpha', 'code' => 'A']);
    FilterFakeProject::create(['name' => 'Beta', 'code' => 'B']);

    $view = new FakeView();
    $table = new ViewTable($view, FilterFakeProject::query());
    $table->add(new TextColumn(base: FilterFakeProject::class, name: 'Name', key: 'name'));

    $filtered = new FilterableViewTable($table, FilterFakeProject::query());

    $result = $filtered->render();
    $data = $result->resolve();

    expect($data)->toHaveCount(2);
});

it('applies multiple filters', function () {
    FilterFakeProject::create(['name' => 'Alpha', 'code' => 'A']);
    FilterFakeProject::create(['name' => 'Alpha', 'code' => 'B']);
    FilterFakeProject::create(['name' => 'Beta', 'code' => 'A']);

    $view = new FakeView();
    $table = new ViewTable($view, FilterFakeProject::query());
    $table->add(new TextColumn(base: FilterFakeProject::class, name: 'Name', key: 'name'));
    $table->add(new TextColumn(base: FilterFakeProject::class, name: 'Code', key: 'code'));

    $filtered = new FilterableViewTable($table, FilterFakeProject::query());
    $filtered->add(new FakeFilter('name', 'Alpha'));
    $filtered->add(new FakeFilter('code', 'A'));

    $result = $filtered->render();
    $data = $result->resolve();

    expect($data)->toHaveCount(1)
        ->and($data[0]['name'])->toBe('Alpha')
        ->and($data[0]['code'])->toBe('A');
});
