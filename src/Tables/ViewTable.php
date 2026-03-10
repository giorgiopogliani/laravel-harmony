<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Override;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\DataSource;
use Performing\Harmony\Contracts\DataTable;
use Performing\Harmony\Contracts\View;

/**
 * @template T
 *
 * @implements DataTable<T>
 */
final class ViewTable implements DataTable
{
    /**
     * @param  Collection<int, Column<T>>  $columns
     */
    public function __construct(
        private readonly View $view,
        private readonly DataSource $record,
        private Collection $columns = new Collection(),
    ) {}

    /** @param Column<T> $column */
    public function add(Column $column): void
    {
        $this->columns = $this->columns->add($column);
    }

    /** @return array<Column<T>> */
    #[Override]
    public function attributes(): array
    {
        return $this->columns->all();
    }

    #[Override]
    /** @return array<Column<T>> */
    public function columns(): array
    {
        $viewColumns = $this->view->columns();

        if (empty($viewColumns)) {
            return $this->columns->all();
        }

        $columns = [];

        foreach ($viewColumns as $column) {
            $found = collect($this->columns)
                ->first(static fn (Column $col) => $col->key() === $column);

            if ($found !== null) {
                $columns[] = $found;
            }
        }

        return empty($columns) ? $this->columns->all() : $columns;
    }

    #[Override]
    public function additional(): array
    {
        return [
            'columns' => $this->columns(),
            'attributes' => $this->attributes(),
        ];
    }

    #[Override]
    public function render(): ResourceCollection
    {
        return $this->record->present($this);
    }
}
