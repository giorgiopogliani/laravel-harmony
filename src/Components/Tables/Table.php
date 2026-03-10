<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasMake;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\DataRecords\TableDataSource;
use Performing\Harmony\Tables\FilterableViewTable;
use Performing\Harmony\Tables\ScrollableViewTable;
use Performing\Harmony\Tables\StaticTable;

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

    public function toArray(): mixed
    {
        if (is_null($this->rows)) {
            return [];
        }

        $source = new TableDataSource(
            query: $this->rows,
            sorters: $this->sorters,
            perPage: $this->getPerPage(),
            resource: $this->resource,
            metadata: [
                'key' => $this->filtersKey,
                'endpoint' => request()->url(),
                'query' => $this->getQuery(),
            ],
        );

        $table = new StaticTable($source);

        foreach ($this->columns as $column) {
            if ($column instanceof Column) {
                $table->add($column);
            }
        }

        $filtered = new FilterableViewTable($table);

        foreach ($this->filters as $filter) {
            $filtered->add($filter);
        }

        if ($this->scrollable) {
            return (new ScrollableViewTable($filtered))->render();
        }

        return $filtered->render();
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
