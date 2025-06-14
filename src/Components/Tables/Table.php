<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pipeline\Pipeline;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasMake;
use Spatie\LaravelData\Data;

class Table extends Component
{
    use HasMake;

    /** @var TableColumn[] */
    protected array $columns = [];

    /** @var TableFilter[] */
    protected array $filters = [];

    /** @var TableAction[] */
    protected array $actions = [];

    /** @var TableSorters[] */
    protected array $sorters = [];

    /** @var Builder */
    protected $rows = null;

    protected bool $selectable = false;

    protected ?string $resource = null;

    protected array $query = ['per_page' => 15];

    protected string $filtersKey = 'filters';

    public function columns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function actions(array $actions)
    {
        $this->actions = $actions;

        return $this;
    }

    public function filters(array $filters)
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    public function filtersKey(string $key)
    {
        $this->filtersKey = $key;

        return $this;
    }

    public function sorters(array $sorters)
    {
        $this->sorters = array_merge($this->sorters, $sorters);

        return $this;
    }

    public function selectable()
    {
        $this->selectable = true;

        return $this;
    }

    public function rows($data, ?string $class = null)
    {
        $this->rows = $data;
        $this->resource = $class;

        return $this;
    }

    public function toArray(): array
    {
        $this->applyFilters();
        $this->applySorting();
        $this->applyPaginate();

        return [
            'key' => $this->filtersKey,
            'endpoint' => request()->url(),
            'columns' => $this->columns,
            'rows' => $this->rows,
            'filters' => $this->filters,
            'query' => $this->getQuery(),
            'selectable' => $this->selectable,
            'actions' => $this->actions,
        ];
    }

    protected function applyFilters()
    {
        app(Pipeline::class)
            ->send($this->rows)
            ->through($this->filters)
            ->thenReturn();
    }

    protected function applyPaginate()
    {
        if (is_null($this->rows)) {
            return;
        }

        $this->rows = $this->rows
            ->paginate($this->getPerPage(), ['*'], 'page')
            ->withQueryString();

        $class = $this->resource;

        $this->rows->through(function ($item) use ($class) {
            if (is_null($class)) {
                $data = $item->toArray();
            } elseif (is_a($class, JsonResource::class, true)) {
                $data = $class::make($item)->resolve();
            } elseif (is_a($class, Data::class, true)) {
                $data = $class::from($item)->toArray();
            }

            foreach ($this->columns as $column) {
                if ($column->format instanceof \Closure) {
                    $data[$column->getKey()] = call_user_func($column->format, $item, $column);
                }
            }

            return $data;
        });
    }

    protected function applySorting()
    {
        if (request()->has('sort')) {
            $column = str_replace('-', '', request()->input('sort'));
            $direction = str_starts_with(request()->input('sort'), '-') ? 'asc' : 'desc';
            if (array_key_exists($column, $this->sorters)) {
                $this->sorters[$column]($this->rows, $direction);
            } else {
                $this->rows->orderBy($column, $direction);
            }
        }
    }

    protected function getQuery(): array
    {
        return collect($this->filters)
            ->mapWithKeys(fn (TableFilter $filter) => [
                $filter->getKey() => request()->input("$this->filtersKey." . $filter->getKey()),
            ])
            ->merge([
                'search' => request()->input("$this->filtersKey.search"),
            ])
            ->filter()
            ->merge([
                'per_page' => $this->getPerPage(),
            ])
            ->toArray();
    }

    public function getPerPage()
    {
        return (int) request()->input("per_page", $this->query['per_page']);
    }
}
