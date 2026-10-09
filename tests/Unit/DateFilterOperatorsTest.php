<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\FilterableAdvanced;
use Performing\Harmony\Contracts\Identity;
use Performing\Harmony\Contracts\Validation;
use Performing\Harmony\Contracts\Value;
use Performing\Harmony\Contracts\Visibility;
use Performing\Harmony\Fields\Concerns\HasDateFilterOperators;
use Performing\Harmony\Fields\FieldIdentity;
use Performing\Harmony\Fields\FieldValidation;
use Performing\Harmony\Fields\FieldVisibility;
use Performing\Harmony\Fields\Fields\DateField;
use Performing\Harmony\Filters\FieldColumnFilter;
use Performing\Harmony\RenderTypes\DateRenderType;
use Performing\Harmony\Sources\SavedFilterSource;

uses(Tests\TestCase::class, RefreshDatabase::class);

class DateFilterTestRecord extends Model
{
    protected $table = 'date_filter_records';

    protected $guarded = [];

    public $timestamps = false;
}

class DateFilterTestField implements Field, FilterableAdvanced
{
    use HasDateFilterOperators;

    public function __construct(
        public readonly Identity $identity,
        public readonly Validation $validation,
        public readonly Visibility $visibility,
    ) {}

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

function filterDates(string $operator, ?string $preset): array
{
    $field = new DateFilterTestField(
        new FieldIdentity('published_on', 'Published on', 'published_on', new DateRenderType),
        new FieldValidation,
        new FieldVisibility,
    );

    $encoded = $preset === null ? $operator : "{$operator}__{$preset}";
    $filter = new FieldColumnFilter(new SavedFilterSource(['published_on' => $encoded]), $field);

    return $filter->apply(DateFilterTestRecord::query())->orderBy('day')->pluck('day')->all();
}

beforeEach(function () {
    CarbonImmutable::setTestNow('2026-10-09 12:00:00');

    Schema::create('date_filter_records', function ($table) {
        $table->id();
        $table->string('day');
        $table->json('content');
    });

    foreach ([
        '2026-09-01',
        '2026-09-28',
        '2026-09-30',
        '2026-10-01',
        '2026-10-04',
        '2026-10-05',
        '2026-10-08',
        '2026-10-09',
        '2026-10-11',
        '2026-10-12',
        '2026-10-31',
        '2026-11-01',
    ] as $day) {
        DateFilterTestRecord::create([
            'day' => $day,
            'content' => json_encode(['published_on' => $day]),
        ]);
    }
});

afterEach(function () {
    CarbonImmutable::setTestNow();
});

it('resolves today and yesterday instead of comparing preset strings', function () {
    expect(filterDates('eq', 'today'))->toBe(['2026-10-09'])
        ->and(filterDates('eq', 'yesterday'))->toBe(['2026-10-08']);
});

it('includes the boundaries of this week and last week', function () {
    expect(filterDates('eq', 'this_week'))->toBe([
        '2026-10-05', '2026-10-08', '2026-10-09', '2026-10-11',
    ])->and(filterDates('eq', 'last_week'))->toBe([
        '2026-09-28', '2026-09-30', '2026-10-01', '2026-10-04',
    ]);
});

it('includes the boundaries of this month and last month', function () {
    expect(filterDates('eq', 'this_month'))->toBe([
        '2026-10-01', '2026-10-04', '2026-10-05', '2026-10-08',
        '2026-10-09', '2026-10-11', '2026-10-12', '2026-10-31',
    ])->and(filterDates('eq', 'last_month'))->toBe([
        '2026-09-01', '2026-09-28', '2026-09-30',
    ]);
});

it('uses the period start for gte and the period end for lte', function () {
    expect(filterDates('gte', 'this_week'))->toBe([
        '2026-10-05', '2026-10-08', '2026-10-09', '2026-10-11',
        '2026-10-12', '2026-10-31', '2026-11-01',
    ])->and(filterDates('lte', 'this_week'))->toBe([
        '2026-09-01', '2026-09-28', '2026-09-30',
        '2026-10-01', '2026-10-04', '2026-10-05',
        '2026-10-08', '2026-10-09', '2026-10-11',
    ])->and(filterDates('gte', 'today'))->toBe([
        '2026-10-09', '2026-10-11', '2026-10-12', '2026-10-31', '2026-11-01',
    ])->and(filterDates('lte', 'last_month'))->toBe([
        '2026-09-01', '2026-09-28', '2026-09-30',
    ]);
});

it('supports strict comparisons outside the selected period', function () {
    expect(filterDates('greater_than', 'this_week'))->toBe([
        '2026-10-12', '2026-10-31', '2026-11-01',
    ])->and(filterDates('less_than', 'this_week'))->toBe([
        '2026-09-01', '2026-09-28', '2026-09-30',
        '2026-10-01', '2026-10-04',
    ])->and(filterDates('greater_than', 'today'))->toBe([
        '2026-10-11', '2026-10-12', '2026-10-31', '2026-11-01',
    ])->and(filterDates('less_than', 'last_month'))->toBe([]);
});

it('ignores unknown or absent presets', function () {
    expect(filterDates('eq', 'unknown'))->toHaveCount(12)
        ->and(filterDates('eq', null))->toHaveCount(12);
});

it('does not register DateField as advanced yet', function () {
    expect(is_subclass_of(DateField::class, FilterableAdvanced::class))->toBeFalse();
});
