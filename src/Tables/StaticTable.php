<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\DataTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Override;

/**
 * @template T
 * @implements DataTable<T>
 */
final class StaticTable implements DataTable
{
    use HasPaginatedQuery;

    /**
     * @param  Builder<T>  $query
     * @param  Collection<int, Column<T>>  $columns
     */
    public function __construct(
        private Builder $query,
        private Collection $columns = new Collection(),
    ) {}

    #[Override]
    public function query(): Builder
    {
        return $this->query;
    }

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
    public function additional(): array
    {
        return [
            'columns' => $this->columns(),
            'attributes' => $this->attributes(),
        ];
    }

    #[Override]
    public function render(): mixed
    {
        return JsonResource::collection($this->rows())->additional($this->additional());
    }
}
