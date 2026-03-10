<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Deprecated;
use Illuminate\Http\Resources\Json\JsonResource;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasMake;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\DataRecords\TableDataSource;
use Performing\Harmony\Tables\FilterableViewTable;
use Performing\Harmony\Tables\ScrollableViewTable;
use Performing\Harmony\Tables\StaticTable;
use Spatie\LaravelData\Data;

/** @deprecated */
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

    public function toArray(): array|\Inertia\ScrollProp
    {
        if (is_null($this->rows)) {
            return [];
        }

        $source = new TableDataSource(
            query: $this->rows,
            record: fn (mixed $model) => $this->transformRowItem($model),
            sorters: $this->sorters,
            perPage: $this->getPerPage(),
        );

        $table = new StaticTable($source);

        $filtered = new FilterableViewTable($table);

        foreach ($this->filters as $filter) {
            $filtered->add($filter);
        }

        if ($this->scrollable) {
            return (new ScrollableViewTable($filtered))->render();
        }

        return $filtered->render();
    }

    protected function transformRowItem(mixed $item): array
    {
        $data = $this->resolveItemData($item);

        foreach ($this->columns as $column) {
            if ($column instanceof Column) {
                $data[$column->key()] = $column->value($data) ?? null;
            } elseif ($column->format instanceof \Closure) {
                $data[$column->getKey()] = call_user_func($column->format, $item, $column);
            }
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

    public function getInput(string $key, mixed $default = null)
    {
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
