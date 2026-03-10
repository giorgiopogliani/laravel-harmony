<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Performing\Harmony\Columns\TextColumn;
use Performing\Harmony\Contracts\Record;
use Performing\Harmony\Contracts\View;
use Performing\Harmony\Tables\ViewTable;

uses(Tests\TestCase::class, RefreshDatabase::class);

class ViewFakeProject extends \Illuminate\Database\Eloquent\Model implements Record
{
    protected $table = 'view_fake_projects';

    protected $guarded = [];

    public function model(): mixed
    {
        return $this;
    }
}

class FakeView implements View
{
    public function __construct(private readonly array $visibleColumns = []) {}

    public function type(): string
    {
        return 'table';
    }

    public function columns(): array
    {
        return $this->visibleColumns;
    }

    public function grouped(): ?string
    {
        return null;
    }

    public function filters(): array
    {
        return [];
    }
}

beforeEach(function () {
    Schema::create('view_fake_projects', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('code');
        $table->timestamps();
    });
});

it('returns all columns when view has no column filter', function () {
    $view = new FakeView();
    $table = new ViewTable($view, ViewFakeProject::query());

    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Name', key: 'name'));
    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Code', key: 'code'));

    expect($table->columns())->toHaveCount(2);
});

it('filters columns based on view', function () {
    $view = new FakeView(['name']);
    $table = new ViewTable($view, ViewFakeProject::query());

    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Name', key: 'name'));
    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Code', key: 'code'));

    expect($table->columns())->toHaveCount(1)
        ->and($table->columns()[0]->key())->toBe('name');
});

it('attributes returns all columns regardless of view', function () {
    $view = new FakeView(['name']);
    $table = new ViewTable($view, ViewFakeProject::query());

    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Name', key: 'name'));
    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Code', key: 'code'));

    expect($table->attributes())->toHaveCount(2);
});

it('additional includes columns and attributes', function () {
    $view = new FakeView();
    $table = new ViewTable($view, ViewFakeProject::query());

    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Name', key: 'name'));

    $additional = $table->additional();

    expect($additional)->toHaveKey('columns')
        ->and($additional)->toHaveKey('attributes');
});

it('renders rows through QueryRecord', function () {
    ViewFakeProject::create(['name' => 'Alpha', 'code' => 'A']);
    ViewFakeProject::create(['name' => 'Beta', 'code' => 'B']);

    $view = new FakeView();
    $table = new ViewTable($view, ViewFakeProject::query());

    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Name', key: 'name'));
    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Code', key: 'code'));

    $result = $table->render();
    $data = $result->resolve();

    expect($data)->toHaveCount(2)
        ->and($data[0]['name'])->toBe('Alpha')
        ->and($data[1]['code'])->toBe('B');
});

it('render only includes view-filtered columns in rows', function () {
    ViewFakeProject::create(['name' => 'Alpha', 'code' => 'A']);

    $view = new FakeView(['name']);
    $table = new ViewTable($view, ViewFakeProject::query());

    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Name', key: 'name'));
    $table->add(new TextColumn(base: ViewFakeProject::class, name: 'Code', key: 'code'));

    $result = $table->render();
    $data = $result->resolve();

    expect($data[0])->toHaveKey('name')
        ->and($data[0])->not->toHaveKey('code');
});
