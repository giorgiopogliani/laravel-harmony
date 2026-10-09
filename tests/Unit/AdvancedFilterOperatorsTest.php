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

it('lets operators own their input configuration and defaults', function () {
    $options = [['label' => 'Alpha', 'value' => 'Alpha']];
    $operator = new Equals(inputType: 'select', options: $options, rules: ['required'], defaultValue: 'Alpha');

    expect($operator->key())->toBe('equals')
        ->and($operator->inputType())->toBe('select')
        ->and($operator->options())->toBe($options)
        ->and($operator->rules())->toBe(['required'])
        ->and($operator->default())->toBe('Alpha')
        ->and((new IsEmpty)->inputType())->toBeNull()
        ->and((new IsEmpty)->rules())->toBe([])
        ->and((new IsOneOf($options))->options())->toBe($options)
        ->and((new IsOneOf)->rules())->toBe(['required', 'array', 'min:1']);
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

    expect($date->operators()[0]->inputType())->toBe('date')
        ->and($date->operators()[0]->rules())->toBe(['required', 'date'])
        ->and($select->operators()[0]->inputType())->toBe('select')
        ->and($select->operators()[0]->options())->toBe([['label' => 'Alpha', 'value' => 'Alpha']]);
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

it('rejects non-array values for set operators', function () {
    (new IsOneOf)->apply(AdvancedFilterTestRecord::query(), 'name', 'Alpha');
})->throws(InvalidArgumentException::class);
