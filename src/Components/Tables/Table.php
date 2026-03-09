<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;
use Spatie\LaravelData\Data;

class Table implements Component
{
    use IsConditional;

    /** @var TableColumn[] */
    protected array $columns = [];

    /** @var TableFilter[] */
    protected array $filters = [];

    /** @var array<string, callable> */
    protected array $sorters = [];

    protected mixed $rows = null;

    protected ?string $resource = null;

    protected array $query = ['per_page' => 15];

    protected string $filtersKey = '';

    protected bool $scrollable = false;

    protected ?string $group = null;

    protected Request $request;

    public function __construct(?Request $request = null)
    {
        $this->request = $request ?? app('request');
    }

    public static function make(?Request $request = null): static
    {
        return new static($request);
    }

    public function columns(array $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    public function filters(array $filters): static
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    public function filtersKey(string $key): static
    {
        $this->filtersKey = $key;

        return $this;
    }

    public function sorters(array $sorters): static
    {
        $this->sorters = array_merge($this->sorters, $sorters);

        return $this;
    }

    public function rows(mixed $data, ?string $class = null): static
    {
        $this->rows = $data;
        $this->resource = $class;

        return $this;
    }

    public function scrollable(): static
    {
        $this->scrollable = true;

        return $this;
    }

    public function group(string $column): static
    {
        $this->group = $column;

        return $this;
    }

    #[\Override]
    public function toArray(): array
    {
        $this->resolveFilters();
        $this->applyFilters();
        $this->applySorting();

        $extra = [
            'key' => $this->filtersKey,
            'endpoint' => $this->request->url(),
            'columns' => collect($this->columns),
            'filters' => collect($this->filters),
            'query' => $this->getQuery(),
            'group' => $this->group,
        ];

        if ($this->group) {
            $models = $this->rows->get();

            return [
                'rows' => $this->applyGrouping($models),
                ...$extra,
            ];
        }

        $this->applyPaginate();

        return [
            'rows' => $this->rows,
            ...$extra,
        ];
    }

    protected function resolveFilters(): void
    {
        foreach ($this->filters as $filter) {
            $key = $filter->getKey();
            $value = $this->getInput($key, $filter->getDefault());
            $active = $this->hasInput($key);
            $filter->resolve($value, $active);
        }
    }

    protected function applyFilters(): void
    {
        if (is_null($this->rows)) {
            return;
        }

        $this->rows = app(Pipeline::class)
            ->send($this->rows)
            ->through($this->filters)
            ->thenReturn();
    }

    protected function applyPaginate(): void
    {
        if (is_null($this->rows)) {
            return;
        }

        $this->rows = $this->rows
            ->paginate($this->getPerPage(), ['*'], 'page')
            ->withQueryString();

        $this->rows->through(fn ($item) => $this->transformRowItem($item));
    }

    public function applyGrouping(Collection $models): Collection
    {
        $column = collect($this->columns)->first(fn (TableColumn $col) => $col->getKey() === $this->group);
        $groupAsClosure = $column?->groupAs;

        $group = [];

        foreach ($models as $model) {
            $row = $this->transformRowItem($model);

            if ($groupAsClosure) {
                $groupKey = $groupAsClosure($model) ?? '__nogroup__';
            } else {
                $groupKey = $row[$this->group] ?? '__nogroup__';
            }

            $group[$groupKey] ??= [];
            $group[$groupKey][] = $row;
        }

        return collect($group);
    }

    protected function transformRowItem(mixed $item): array
    {
        $data = $this->resolveItemData($item);

        foreach ($this->columns as $column) {
            if (! $column->format instanceof \Closure) {
                continue;
            }

            $data[$column->getKey()] = call_user_func($column->format, $item, $column);
        }

        return $data;
    }

    protected function resolveItemData(mixed $item): array
    {
        $class = $this->resource;

        if (is_null($class)) {
            return $item->toArray();
        }

        if (is_a($class, JsonResource::class, true)) {
            return $class::make($item)->resolve();
        }

        if (is_a($class, Data::class, true)) {
            return $class::from($item)->toArray();
        }

        return $item->toArray();
    }

    protected function applySorting(): void
    {
        if (! $this->request->has('sort') || is_null($this->rows)) {
            return;
        }

        $sortParam = $this->request->input('sort');
        $column = str_replace('-', '', $sortParam);
        $direction = str_starts_with($sortParam, '-') ? 'desc' : 'asc';

        if (array_key_exists($column, $this->sorters)) {
            $this->sorters[$column]($this->rows, $direction);
        } else {
            $this->rows->orderBy($column, $direction);
        }
    }

    protected function getQuery(): array
    {
        return collect($this->filters)
            ->mapWithKeys(fn (TableFilter $filter) => [
                $filter->getKey() => $this->getInput($filter->getKey()),
            ])
            ->merge([
                'search' => $this->getInput('search'),
            ])
            ->filter()
            ->merge([
                'per_page' => $this->getPerPage(),
            ])
            ->toArray();
    }

    public function getInput(string $key, mixed $default = null): mixed
    {
        if (strlen($this->filtersKey) == 0) {
            return $this->request->input($key, $default);
        }

        return $this->request->input($this->filtersKey . '.' . $key, $default);
    }

    public function hasInput(string $key): bool
    {
        if (strlen($this->filtersKey) == 0) {
            return $this->request->has($key);
        }

        return $this->request->has($this->filtersKey . '.' . $key);
    }

    public function getPerPage(): int
    {
        return (int) $this->getInput('per_page', $this->query['per_page']);
    }
}
