<?php

declare(strict_types=1);

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Performing\Harmony\Contracts\Filterable;
use Performing\Harmony\Contracts\FilterableAdvanced;
use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Fields\Concerns\HasDateFilterOperators;
use Performing\Harmony\Fields\Concerns\HasSelectFilterOperators;
use Performing\Harmony\Fields\Concerns\HasTextFilterOperators;
use Performing\Harmony\Filters\Operators\Contains;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\GreaterThan;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNoneOf;
use Performing\Harmony\Filters\Operators\IsNotEmpty;
use Performing\Harmony\Filters\Operators\IsOneOf;
use Performing\Harmony\Filters\Operators\NotContains;
use Performing\Harmony\Filters\Operators\NotEquals;

uses(Tests\TestCase::class, RefreshDatabase::class);

class AdvancedFilterTestRecord extends Model
{
    protected $table = 'advanced_filter_records';

    protected $guarded = [];

    public $timestamps = false;
}

beforeEach(function () {
    Schema::create('advanced_filter_records', function ($table) {
        $table->id();
        $table->string('name')->nullable();
        $table->integer('quantity')->nullable();
    });

    AdvancedFilterTestRecord::insert([
        ['name' => 'Alpha', 'quantity' => 5],
        ['name' => 'Beta', 'quantity' => 10],
        ['name' => '', 'quantity' => 15],
        ['name' => null, 'quantity' => null],
    ]);
});

it('keeps the advanced filter contract independent of the old one', function () {
    expect(is_subclass_of(FilterableAdvanced::class, Filterable::class))->toBeFalse()
        ->and(method_exists(FilterOperator::class, 'requiresValue'))->toBeFalse();

    $field = new class implements FilterableAdvanced
    {
        use HasTextFilterOperators;
    };

    expect($field->operators())->toHaveCount(8);

    foreach ($field->operators() as $operator) {
        expect($operator)->toBeInstanceOf(FilterOperator::class);
    }
});

it('keeps final operators free of input configuration', function () {
    $operator = new Equals;

    expect($operator->key())->toBe('equals')
        ->and(method_exists(FilterOperator::class, 'default'))->toBeFalse()
        ->and(method_exists($operator, 'default'))->toBeFalse()
        ->and(method_exists(FilterOperator::class, 'options'))->toBeFalse()
        ->and(method_exists($operator, 'options'))->toBeFalse();
});

it('offers date and selection operator presets without changing concrete fields', function () {
    $date = new class implements FilterableAdvanced
    {
        use HasDateFilterOperators;
    };

    $select = new class implements FilterableAdvanced
    {
        use HasSelectFilterOperators;

        public function getOptions(): array
        {
            return [['label' => 'Alpha', 'value' => 'Alpha']];
        }
    };

    expect($date->operators()[0])->toBeInstanceOf(Equals::class)
        ->and($select->operators()[0])->toBeInstanceOf(Equals::class)
        ->and($select->operators()[2])->toBeInstanceOf(IsOneOf::class);
});

it('applies comparison and pattern operators directly to a query', function () {
    $query = (new Equals)->apply(AdvancedFilterTestRecord::query(), 'name', 'Alpha');
    expect($query->pluck('name')->all())->toBe(['Alpha']);

    $query = (new NotEquals)->apply(AdvancedFilterTestRecord::query(), 'name', 'Alpha');
    expect($query->pluck('name')->all())->toBe(['Beta', '']);

    $query = (new GreaterThan)->apply(AdvancedFilterTestRecord::query(), 'quantity', 5);
    expect($query->pluck('name')->all())->toBe(['Beta', '']);

    $query = (new Contains)->apply(AdvancedFilterTestRecord::query(), 'name', 'ph');
    expect($query->pluck('name')->all())->toBe(['Alpha']);

    $query = (new NotContains)->apply(AdvancedFilterTestRecord::query(), 'name', 'ph');
    expect($query->pluck('name')->all())->toBe(['Beta', '']);
});

it('accepts expressions as well as column names', function () {
    $query = (new Equals)->apply(AdvancedFilterTestRecord::query(), DB::raw('LOWER(name)'), 'alpha');

    expect($query->pluck('name')->all())->toBe(['Alpha']);
});

it('applies set and empty operators with no field-side filtering', function () {
    expect((new IsOneOf)->apply(AdvancedFilterTestRecord::query(), 'name', ['Alpha', 'Beta'])->count())->toBe(2)
        ->and((new IsNoneOf)->apply(AdvancedFilterTestRecord::query(), 'name', ['Alpha'])->count())->toBe(2)
        ->and((new IsEmpty)->apply(AdvancedFilterTestRecord::query(), 'name', null)->count())->toBe(2)
        ->and((new IsNotEmpty)->apply(AdvancedFilterTestRecord::query(), 'name', null)->count())->toBe(2);
});

it('accepts comma-separated and JSON-encoded set values', function () {
    expect((new IsOneOf)->apply(AdvancedFilterTestRecord::query(), 'name', 'Alpha,Beta')->count())->toBe(2)
        ->and((new IsNoneOf)->apply(AdvancedFilterTestRecord::query(), 'name', '["Alpha"]')->count())->toBe(2);
});

it('rejects non-string, non-array set values', function () {
    (new IsOneOf)->apply(AdvancedFilterTestRecord::query(), 'name', 123);
})->throws(InvalidArgumentException::class);

it('implements all new operators directly without abstract base classes', function () {
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
