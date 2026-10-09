<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Override;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Contracts\Filterable;
use Performing\Harmony\Contracts\FilterableAdvanced;
use Performing\Harmony\Contracts\FilterOperator;
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
        if ($this->field instanceof FilterableAdvanced) {
            return $this->field->identity->type->value();
        }

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
        if ($this->field instanceof FilterableAdvanced) {
            return $this->applyAdvanced($query);
        }

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

    private function applyAdvanced(Builder $query): Builder
    {
        $raw = $this->source->get($this->key());

        if ($raw === null || $raw === '') {
            return $query;
        }

        [$key, $encoded] = array_pad(explode('__', $raw, 2), 2, null);

        foreach ($this->field->operators() as $operator) {
            if ($operator->key() !== $key) {
                continue;
            }

            if ($operator->inputType() === null) {
                $value = $operator->default();
            } elseif ($encoded === null || $encoded === '') {
                $value = $operator->default();
            } elseif ($operator->inputType() === 'multiselect') {
                $value = $this->decodeSelection($encoded);
            } else {
                $value = $encoded;
            }

            if ($operator->inputType() !== null) {
                if ($value === null || $value === '') {
                    return $query;
                }

                Validator::make(['value' => $value], ['value' => $operator->rules()])->validate();
            }

            // Use Laravel's JSON selector so the database grammar handles escaping.
            return $operator->apply($query, 'content->'.$this->field->identity->uuid, $value);
        }

        // A field cannot be filtered by an operator it does not advertise.
        return $query;
    }

    /** @return list<mixed>|null */
    private function decodeSelection(string $encoded): ?array
    {
        if (str_starts_with($encoded, '[')) {
            $decoded = json_decode($encoded, true);

            return is_array($decoded) && array_is_list($decoded) ? $decoded : null;
        }

        return explode(',', $encoded);
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

    public function options(): array
    {
        if ($this->field instanceof FilterableAdvanced) {
            return [];
        }

        if ($this->field instanceof HasOptions) {
            return $this->field->getOptions();
        }

        return [];
    }

    #[Override]
    public function jsonSerialize(): array
    {
        $data = [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type(),
            'inline' => $this->inline(),
            'options' => $this->options(),
            'value' => $this->source->get($this->key()),
            'encoding' => 'operator',
        ];

        if ($this->field instanceof FilterableAdvanced) {
            $data['operators'] = array_map(
                static fn (FilterOperator $operator): array => [
                    'key' => $operator->key(),
                    'label' => $operator->label(),
                    'input' => $operator->inputType() === null ? null : [
                        'type' => $operator->inputType(),
                        'options' => $operator->options(),
                        'default' => $operator->default(),
                    ],
                ],
                $this->field->operators(),
            );
        }

        return $data;
    }
}
