<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Override;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Contracts\Filterable;
use Performing\Harmony\Contracts\FilterSource;
use Performing\Harmony\Contracts\HasOptions;

final readonly class FieldColumnFilter implements Filter
{
    private const array ARRAY_FIELD_TYPES = ['users', 'multiselect'];

    public function __construct(
        private FilterSource $source,
        private Field $field,
    ) {}

    #[Override]
    public function key(): string
    {
        return $this->field->identity->handle;
    }

    #[Override]
    public function label(): string
    {
        return $this->field->identity->name;
    }

    #[Override]
    public function type(): string
    {
        if ($this->field instanceof Filterable) {
            return $this->field->filterType();
        }

        return $this->field
            ->identity
            ->type
            ->value();
    }

    #[Override]
    public function inline(): bool
    {
        return false;
    }

    #[Override]
    public function apply(Builder $query): Builder
    {
        $raw = $this->source->get($this->key());

        if (empty($raw)) {
            return $query;
        }

        [$operator, $values] = explode('__',  $raw, 2);
        $uuid = $this->field->identity->uuid;
        $isArrayField = in_array(
            $this->field
                ->identity
                ->type
                ->value(),
            self::ARRAY_FIELD_TYPES,
            true,
        );

        if ($isArrayField && in_array($operator, ['in', 'not_in'], true)) {
            $parsed = explode(',', $values);
            $jsonValues = json_encode(array_map(static fn ($v) => is_numeric($v) ? (int) $v : $v, $parsed));
            $expression = "JSON_OVERLAPS(content->>'$.\"{$uuid}\"', CAST(? AS JSON))";

            return match ($operator) {
                'in' => $query->whereRaw($expression, [$jsonValues]),
                'not_in' => $query->whereRaw("NOT {$expression}", [$jsonValues]),
            };
        }

        $jsonPath = DB::raw("JSON_VALUE(content, '$.\"{$uuid}\"')");

        if ($this->field->identity->type->value() === 'date') {
            return $this->applyDateFilter($query, $jsonPath, $operator, $values);
        }

        return match ($operator) {
            'in' => $query->whereIn($jsonPath, explode(',', $values)),
            'not_in' => $query->whereNotIn($jsonPath, explode(',', $values)),
            'eq' => $query->where($jsonPath, $values),
            'gte' => $query->where($jsonPath, '>=', $values),
            'lte' => $query->where($jsonPath, '<=', $values),
            default => $query,
        };
    }

    private function applyDateFilter(Builder $query, mixed $jsonPath, string $operator, string $preset): Builder
    {
        $today = CarbonImmutable::today();

        [$start, $end] = match ($preset) {
            'today' => [$today, $today],
            'yesterday' => [$today->subDay(), $today->subDay()],
            'this_week' => [$today->startOfWeek(), $today->endOfWeek()],
            'last_week' => [$today->subWeek()->startOfWeek(), $today->subWeek()->endOfWeek()],
            'this_month' => [$today->startOfMonth(), $today->endOfMonth()],
            'last_month' => [$today->subMonth()->startOfMonth(), $today->subMonth()->endOfMonth()],
            default => [$today, $today],
        };

        return match ($operator) {
            'eq' => $query->whereBetween($jsonPath, [$start->toDateString(), $end->toDateString()]),
            'gte' => $query->where($jsonPath, '>=', $start->toDateString()),
            'lte' => $query->where($jsonPath, '<=', $end->toDateString()),
            default => $query,
        };
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $data = [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type(),
            'inline' => $this->inline(),
            'value' => $this->source->get($this->key()),
            'encoding' => 'operator',
        ];

        if ($this->field instanceof HasOptions) {
            $data['options'] = $this->field->getOptions();
        }

        return $data;
    }
}
