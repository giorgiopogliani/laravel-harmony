<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Performing\Harmony\Columns\BoolColumn;
use Performing\Harmony\Columns\TextColumn;
use Performing\Harmony\Contracts\DataTable;
use Performing\Harmony\Tables\QueryRecord;

uses(Tests\TestCase::class, RefreshDatabase::class);

class FakeProject extends Model
{
    protected $table = 'fake_projects';

    protected $guarded = [];
}

class FakeTable implements DataTable
{
    public function __construct(
        private readonly array $columns,
        private readonly array $additional = [],
    ) {}

    public function attributes(): array
    {
        return $this->columns;
    }

    public function columns(): array
    {
        return $this->columns;
    }

    public function additional(): array
    {
        return $this->additional;
    }

    public function render(): mixed
    {
        return null;
    }
}

beforeEach(function () {
    Schema::create('fake_projects', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('code');
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
});

it('maps records to rows through columns', function () {
    FakeProject::create(['name' => 'Alpha', 'code' => 'A']);
    FakeProject::create(['name' => 'Beta', 'code' => 'B']);

    $columns = [
        new TextColumn(base: FakeProject::class, name: 'Name', key: 'name'),
        new TextColumn(base: FakeProject::class, name: 'Code', key: 'code'),
    ];

    $table = new FakeTable($columns);
    $result = new QueryRecord($table, FakeProject::query())->rows();
    $data = $result->resolve();

    expect($data)->toHaveCount(2)
        ->and($data[0]['name'])->toBe('Alpha')
        ->and($data[0]['code'])->toBe('A')
        ->and($data[1]['name'])->toBe('Beta')
        ->and($data[1]['code'])->toBe('B');
});

it('includes id from model key in each row', function () {
    $project = FakeProject::create(['name' => 'Alpha', 'code' => 'A']);

    $columns = [
        new TextColumn(base: FakeProject::class, name: 'Name', key: 'name'),
    ];

    $table = new FakeTable($columns);
    $result = new QueryRecord($table, FakeProject::query())->rows();
    $data = $result->resolve();

    expect($data[0]['id'])->toBe($project->id);
});

it('includes additional data from table', function () {
    FakeProject::create(['name' => 'Alpha', 'code' => 'A']);

    $columns = [
        new TextColumn(base: FakeProject::class, name: 'Name', key: 'name'),
    ];

    $table = new FakeTable($columns, ['columns' => $columns, 'meta' => 'value']);
    $result = new QueryRecord($table, FakeProject::query())->rows();

    expect($result->additional)->toHaveKey('columns')
        ->and($result->additional)->toHaveKey('meta', 'value');
});

it('works with different column types', function () {
    FakeProject::create(['name' => 'Alpha', 'code' => 'A', 'active' => true]);

    $columns = [
        new TextColumn(base: FakeProject::class, name: 'Name', key: 'name'),
        new BoolColumn(base: FakeProject::class, name: 'Active', key: 'active'),
    ];

    $table = new FakeTable($columns);
    $result = new QueryRecord($table, FakeProject::query())->rows();
    $data = $result->resolve();

    expect($data[0]['name'])->toBe('Alpha')
        ->and($data[0]['active'])->toBeTruthy();
});

it('paginates results', function () {
    for ($i = 1; $i <= 20; $i++) {
        FakeProject::create(['name' => "Project {$i}", 'code' => "P{$i}"]);
    }

    $columns = [
        new TextColumn(base: FakeProject::class, name: 'Name', key: 'name'),
    ];

    $table = new FakeTable($columns);
    $result = new QueryRecord($table, FakeProject::query())->rows();
    $data = $result->resolve();

    expect($data)->toHaveCount(15);
});
