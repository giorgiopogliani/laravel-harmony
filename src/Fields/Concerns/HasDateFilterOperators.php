<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Contracts\FilterOperator;
use Performing\Harmony\Filters\Operators\DateEquals;
use Performing\Harmony\Filters\Operators\DateGreaterThanOrEqual;
use Performing\Harmony\Filters\Operators\DateLessThanOrEqual;
use Performing\Harmony\Filters\Operators\IsEmpty;
use Performing\Harmony\Filters\Operators\IsNotEmpty;

trait HasDateFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            new DateEquals,
            new DateGreaterThanOrEqual,
            new DateLessThanOrEqual,
            new IsEmpty,
            new IsNotEmpty,
        ];
    }

    public function apply(Builder $query, FilterOperator $operator, mixed $value): Builder
    {
        $column = 'content->'.$this->identity->uuid;

        if ($operator->key() === 'is_empty') {
            return $query->where(static function (Builder $query) use ($column): void {
                $query->whereNull($column)->orWhere($column, '');
            });
        }

        if ($operator->key() === 'is_not_empty') {
            return $query->whereNotNull($column)->where($column, '!=', '');
        }

        $period = $this->datePeriod($value);

        if ($period === null) {
            return $query;
        }

        [$start, $end] = $period;

        return match ($operator->key()) {
            'eq' => $query->whereBetween($column, [$start->toDateString(), $end->toDateString()]),
            'gte' => $query->where($column, '>=', $start->toDateString()),
            'lte' => $query->where($column, '<=', $end->toDateString()),
            default => $query,
        };
    }

    /** @return array{CarbonImmutable, CarbonImmutable}|null */
    private function datePeriod(mixed $value): ?array
    {
        if (!is_string($value)) {
            return null;
        }

        $today = CarbonImmutable::today();
        $weekStart = $today->startOfWeek(CarbonInterface::MONDAY);
        $monthStart = $today->startOfMonth();

        return match ($value) {
            'today' => [$today, $today],
            'yesterday' => [$today->subDay(), $today->subDay()],
            'this_week' => [$weekStart, $weekStart->addDays(6)],
            'last_week' => [$weekStart->subWeek(), $weekStart->subDay()],
            'this_month' => [$monthStart, $monthStart->endOfMonth()],
            'last_month' => [$monthStart->subMonth(), $monthStart->subDay()],
            default => null,
        };
    }
}
