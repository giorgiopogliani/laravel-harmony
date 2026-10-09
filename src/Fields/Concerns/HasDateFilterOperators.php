<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Filters\FilterOperator;

trait HasDateFilterOperators
{
    /** @return list<FilterOperator> */
    public function operators(): array
    {
        return [
            FilterOperator::Equals,
            FilterOperator::GreaterThan,
            FilterOperator::GreaterThanOrEqual,
            FilterOperator::LessThan,
            FilterOperator::LessThanOrEqual,
            FilterOperator::IsEmpty,
            FilterOperator::IsNotEmpty,
        ];
    }

    public function apply(Builder $query, FilterOperator $operator, mixed $value): Builder
    {
        $column = 'content->'.$this->identity->uuid;

        if ($operator === FilterOperator::IsEmpty) {
            return $query->where(static function (Builder $query) use ($column): void {
                $query->whereNull($column)->orWhere($column, '');
            });
        }

        if ($operator === FilterOperator::IsNotEmpty) {
            return $query->whereNotNull($column)->where($column, '!=', '');
        }

        $period = $this->datePeriod($value);

        if ($period === null) {
            return $query;
        }

        [$start, $end] = $period;

        return match ($operator) {
            FilterOperator::Equals => $query->whereBetween($column, [$start->toDateString(), $end->toDateString()]),
            FilterOperator::GreaterThan => $query->where($column, '>', $end->toDateString()),
            FilterOperator::GreaterThanOrEqual => $query->where($column, '>=', $start->toDateString()),
            FilterOperator::LessThan => $query->where($column, '<', $start->toDateString()),
            FilterOperator::LessThanOrEqual => $query->where($column, '<=', $end->toDateString()),
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
