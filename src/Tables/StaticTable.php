<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Override;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\DataSource;
use Performing\Harmony\Contracts\DataTable;
use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Contracts\Record;

/**
 * @template T
 * @template B of Record
 * @implements DataTable<T>
 */
final class StaticTable implements DataTable
{
    /**
     * @param  DataSource<T, B>  $source
     * @param  Collection<int, Column<B>>  $columns
     * @param  Collection<int, Filter>  $filters
     */
    public function __construct(
        public readonly DataSource $source,
        private Collection $columns = new Collection(),
        private Collection $filters = new Collection(),
    ) {}

    /** @param Column<B> $column */
    public function add(Column $column): void
    {
        $this->columns = $this->columns->add($column);
    }

    public function addFilter(Filter $filter): void
    {
        $this->filters->push($filter);
    }

    /** @return array<Column<B>> */
    #[Override]
    public function attributes(): array
    {
        return $this->columns->all();
    }

    #[Override]
    /** @return array<Column<B>> */
    public function columns(): array
    {
        return once(function () {
            $viewColumns = $this->attributes();

            if (empty($viewColumns)) {
                return $this->columns->all();
            }

            $columns = [];

            foreach ($viewColumns as $column) {
                $found = collect($this->columns)
                    ->first(
                        static fn (Column $col) => $col->key() === $column->key(),
                    );

                if ($found !== null) {
                    $columns[] = $found;
                }
            }

            return empty($columns) ? $this->columns->all() : $columns;
        });
    }

    #[Override]
    public function filters(): array
    {
        return $this->filters->all();
    }

    #[Override]
    public function additional(): array
    {
        return [
            ...$this->source->additional(),
            'columns' => $this->columns(),
            'attributes' => $this->attributes(),
            'filters' => $this->filters->all(),
        ];
    }

    #[Override]
    public function render(): ResourceCollection
    {
        return $this->source->present($this);
    }
}
