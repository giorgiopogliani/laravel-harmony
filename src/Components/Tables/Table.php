<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasMake;
use Performing\Harmony\Http\Resources\TableResource;
use Performing\Harmony\Http\TableScrollMetadata;
use Spatie\LaravelData\Data;

class Table extends Component
{
    use HasMake;

    /** @var TableColumn[] */
    protected array $columns = [];

    /** @var TableFilter[] */
    protected array $filters = [];

    /** @var array<string, callable> */
    protected array $sorters = [];

    protected $rows = null;

    protected ?string $resource = null;

    protected array $query = ['per_page' => 15];

    protected string $filtersKey = '';

    protected bool $scrollable = false;

    protected ?string $group = null;

    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function filters(array $filters): self
    {
        $this->filters = array_merge($this->filters, $filters);

        foreach ($this->filters as $filter) {
            $filter->setFiltersKey($this->filtersKey);
        }

        return $this;
    }

    public function filtersKey(string $key): self
    {
        $this->filtersKey = $key;

        return $this;
    }

    public function sorters(array $sorters): self
    {
        $this->sorters = array_merge($this->sorters, $sorters);

        return $this;
    }

    public function rows($data, ?string $class = null): self
    {
        $this->rows = $data;
        $this->resource = $class;

        return $this;
    }

    public function scrollable(): self
    {
        $this->scrollable = true;

        return $this;
    }

    public function group(string $column): self
    {
        $this->group = $column;

        return $this;
    }

    public function toArray(): array|\Inertia\ScrollProp
    {
        $this->applyFilters();
        $this->applySorting();

        $extra = [
            'key' => $this->filtersKey,
            'endpoint' => request()->url(),
            'columns' => collect($this->columns),
            'filters' => collect($this->filters),
            'query' => $this->getQuery(),
            'group' => $this->group,
        ];

        if ($this->group) {
            $results = $this->rows->get()->map(fn($row) => $this->transformRowItem($row));
            return [
                'rows' => $this->applyGrouping($results),
                ...$extra,
            ];
        }

        $this->applyPaginate();

        if ($this->scrollable) {
            return Inertia::scroll(TableResource::collection($this->rows)->additional($extra));
        }

        return [
            'rows' => $this->rows,
            ...$extra,
        ];
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

        $this->rows->through(fn($item) => $this->transformRowItem($item));
    }

    public function applyGrouping(Collection $results): Collection
    {
        $group = collect([]);

        foreach ($results as $row) {
            $groupKey = $row[$this->group] ?? '__nogroup__';
            $group[$groupKey] ??= [];
            $group[$groupKey][] = $row;
        }

        return $group;
    }

    protected function transformRowItem($item): array
    {
        $data = $this->resolveItemData($item);

        foreach ($this->columns as $column) {
            if ($column->format instanceof \Closure) {
                $data[$column->getKey()] = call_user_func($column->format, $item, $column);
            }
        }

        return $data;
    }

    protected function resolveItemData($item): array
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
        if (!request()->has('sort') || is_null($this->rows)) {
            return;
        }

        $sortParam = request()->input('sort');
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
                $filter->getKey() => $this->getInput($filter->getkey()),
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

    public function getInput(string $key, mixed $default = null) {
        if (strlen($this->filtersKey) == 0) {
            return request()->input($key, $default);
        }

        return request()->input($this->filtersKey . '.' . $key, $default);
    }

    public function getPerPage(): int
    {
        return (int) $this->getInput('per_page', $this->query['per_page']);
    }
}
