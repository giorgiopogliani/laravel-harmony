<?php

namespace Performing\Harmony\Components\Tables;

use Performing\Harmony\Components\Component;

class Table extends Component
{
    protected array $columns = [];

    protected array $filters = [];

    protected array $actions = [];

    protected array $sorters = [];

    protected $rows = null;

    protected bool $selectable = false;

    protected ?string $resource = null;

    protected array $query = ['per_page' => 10];

    protected string $filtersKey = 'table';

    public static function make()
    {
        return new static();
    }

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
        // collect($this->rows->getModel()->getFilters())->each(function ($filter) {
        //     $this->applyFilter($filter);
        // });

        // $style = new GithubFiltersStyle($this->filters);
        // $style->apply($this->rows);


        collect($this->filters)->each(function ($filter) {
            $this->applyFilter($filter);
        });
    }

    public function applyFilter($filter)
    {
        $params = request()->input($this->filtersKey . '.' . $filter->name()) ;

        $value = is_array($params)
            ? $params['value'] ?? null
            : $params;

        $operator = is_array($params)
            ? $params['operator'] ?? null
            : null;

        if ($operator) {
            $filter->withOperator($operator);
        }

        if ($filter->hasStandaloneOperator()) {
            $filter->apply($this->rows);
        } elseif (! empty($value)) {
            $filter->withValue($value)->apply($this->rows);
        }
    }

    protected function applyPaginate()
    {
        if (is_null($this->rows)) {
            return;
        }

        $this->rows = $this->rows
            ->paginate($this->getPerPage(), ['*'], $this->filtersKey . '_page')
            ->withQueryString();

        $class = $this->resource;

        $this->rows->through(function ($item) use ($class) {
            if (is_null($class)) {
                $data = $item->toArray();
            } else {
                $data = $class::make($item)->resolve();
            }
            foreach ($this->columns as $column) {
                if ($column->format instanceof \Closure) {
                    $closure = \Closure::bind($column->format, $item);
                    $data[$column->get('key')] = $closure($item, $column);
                }
            }

            return $data;
        });
    }

    protected function applySorting()
    {
        if (request()->has($this->filtersKey . '_sort')) {
            $column = str_replace('-', '', request()->input($this->filtersKey . '_sort'));
            $direction = str_starts_with(request()->input($this->filtersKey . '_sort'), '-') ? 'asc' : 'desc';
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
            ->mapWithKeys(fn ($filter) => [
                $filter->name() => request()->input("$this->filtersKey." . $filter->name()),
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
        return (int) request()->input("{$this->filtersKey}.per_page", $this->query['per_page'] ?? 10);
    }
}
