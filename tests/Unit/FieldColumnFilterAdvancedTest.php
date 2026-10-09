<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\FilterableAdvanced;
use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Contracts\Identity;
use Performing\Harmony\Contracts\Validation;
use Performing\Harmony\Contracts\Value;
use Performing\Harmony\Contracts\Visibility;
use Performing\Harmony\Fields\FieldIdentity;
use Performing\Harmony\Fields\FieldValidation;
use Performing\Harmony\Fields\FieldVisibility;
use Performing\Harmony\Fields\Fields\TextField;
use Performing\Harmony\Filters\FieldColumnFilter;
use Performing\Harmony\Filters\Operators\Contains;
use Performing\Harmony\Filters\Operators\Equals;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNoneOf;
use Performing\Harmony\Filters\Operators\IsOneOf;
use Performing\Harmony\RenderTypes\TextRenderType;
use Performing\Harmony\Sources\SavedFilterSource;

uses(Tests\TestCase::class, RefreshDatabase::class);

class AdvancedFieldColumnFilterRecord extends Model
{
    protected $table = 'advanced_field_column_records';

    protected $guarded = [];

    public $timestamps = false;
}

class AdvancedFieldColumnFilterField implements Field, FilterableAdvanced
{
    /** @param list<FilterOperator> $availableOperators */
    public function __construct(
        public readonly Identity $identity,
        public readonly Validation $validation,
        public readonly Visibility $visibility,
        private readonly array $availableOperators,
    ) {}

    public function operators(): array
    {
        return $this->availableOperators;
    }

    public function getValue(): ?Value
    {
        return null;
    }

    public function setValue(mixed $value): static
    {
        return $this;
    }

    public function toSort(): ?array
    {
        return null;
    }
}

/** @param list<FilterOperator> $operators */
function makeAdvancedFieldColumnFilter(string $raw, array $operators): FieldColumnFilter
{
    $identity = new FieldIdentity('name', 'Name', 'name', new TextRenderType);
    $field = new AdvancedFieldColumnFilterField($identity, new FieldValidation, new FieldVisibility, $operators);

    return new FieldColumnFilter(new SavedFilterSource(['name' => $raw]), $field);
}

beforeEach(function () {
    Schema::create('advanced_field_column_records', function ($table) {
        $table->id();
        $table->json('content');
    });

    AdvancedFieldColumnFilterRecord::insert([
        ['content' => json_encode(['name' => 'Alpha'])],
        ['content' => json_encode(['name' => 'Beta'])],
        ['content' => json_encode(['name' => 'Gamma'])],
        ['content' => json_encode(['name' => ''])],
        ['content' => json_encode(['name' => null])],
    ]);
});

it('serializes advanced operators and their own input configurations', function () {
    $options = [['label' => 'Alpha', 'value' => 'Alpha']];
    $filter = makeAdvancedFieldColumnFilter('equals__Alpha', [
        new Equals(options: $options, defaultValue: 'Alpha'),
        new IsEmpty,
    ]);

    expect($filter->jsonSerialize())->toMatchArray([
        'key' => 'name',
        'type' => 'text',
        'encoding' => 'operator',
        'options' => [],
        'value' => 'equals__Alpha',
        'operators' => [
            [
                'key' => 'equals',
                'label' => __('Equals'),
                'options' => $options,
                'default' => 'Alpha',
            ],
            [
                'key' => 'is_empty',
                'label' => __('Is empty'),
                'options' => [],
                'default' => null,
            ],
        ],
    ]);
});

it('delegates advanced comparisons to the selected operator', function () {
    $filter = makeAdvancedFieldColumnFilter('contains__ph', [new Contains, new Equals]);

    expect($filter->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(1);
});

it('supports array selections encoded as JSON or CSV', function () {
    $operators = [new IsOneOf, new IsNoneOf];

    expect(makeAdvancedFieldColumnFilter('is_one_of__["Alpha","Gamma"]', $operators)
        ->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(2)
        ->and(makeAdvancedFieldColumnFilter('is_one_of__Alpha,Gamma', $operators)
            ->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(2)
        ->and(makeAdvancedFieldColumnFilter('is_none_of__["Alpha"]', $operators)
            ->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(3);
});

it('applies operators without input values', function () {
    $filter = makeAdvancedFieldColumnFilter('is_empty', [new IsEmpty]);
    $notEmpty = makeAdvancedFieldColumnFilter('is_not_empty', [new \Performing\Harmony\Filters\Operators\IsNotEmpty]);

    expect($filter->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(2)
        ->and($notEmpty->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(3);
});

it('uses an operator default when its encoded input is missing', function () {
    $filter = makeAdvancedFieldColumnFilter('equals__', [new Equals(defaultValue: 'Beta')]);

    expect($filter->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(1);
});

it('ignores operators not declared by the field', function () {
    $filter = makeAdvancedFieldColumnFilter('not_supported__Alpha', [new Equals]);

    expect($filter->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(5);
});

it('skips missing or malformed advanced values', function () {
    $missing = makeAdvancedFieldColumnFilter('equals__', [new Equals]);
    $malformed = makeAdvancedFieldColumnFilter('is_one_of__[invalid', [new IsOneOf]);
    $emptySet = makeAdvancedFieldColumnFilter('is_one_of', [new IsOneOf]);
    $emptyContains = makeAdvancedFieldColumnFilter('contains__', [new Contains]);

    expect($missing->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(5)
        ->and($malformed->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(5)
        ->and($emptySet->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(5)
        ->and($emptyContains->apply(AdvancedFieldColumnFilterRecord::query())->count())->toBe(5);
});

it('keeps legacy filter serialization unchanged', function () {
    $identity = new FieldIdentity('name', 'Name', 'name', new TextRenderType);
    $field = new TextField($identity, new FieldValidation, new FieldVisibility);
    $filter = new FieldColumnFilter(new SavedFilterSource(['name' => 'eq__Alpha']), $field);

    expect($filter->jsonSerialize())->toBe([
        'key' => 'name',
        'title' => 'Name',
        'type' => 'text',
        'inline' => false,
        'options' => [],
        'value' => 'eq__Alpha',
        'encoding' => 'operator',
    ]);
});
