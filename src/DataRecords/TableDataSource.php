<?php

declare(strict_types=1);

namespace Performing\Harmony\DataRecords;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Performing\Harmony\Contracts\DataSource;
use Performing\Harmony\Contracts\DataTable;
use Performing\Harmony\Contracts\Filter;

/**
 * @implements DataSource<mixed, mixed>
 */
final class TableDataSource implements DataSource
{
    public function __construct(
        private readonly Builder $query,
        public readonly Closure $record,
        private readonly array $sorters,
        private readonly int $perPage,
    ) {}

    public function present(DataTable $table): ResourceCollection
    {
        $query = array_reduce(
            $table->filters(),
            static fn (Builder $query, Filter $filter) => $filter->apply($query),
            $this->query,
        );

        $this->applySorting($query);

        $data = $query
            ->paginate($this->perPage, ['*'], 'page')
            ->withQueryString()
            ->through(fn (mixed $model) => ($this->record)($model));

        return JsonResource::collection($data)
            ->additional($table->additional());
    }

    private function applySorting(Builder $query): void
    {
        if (! request()->has('sort')) {
            return;
        }

        $sortParam = request()->input('sort');
        $column = str_replace('-', '', $sortParam);
        $direction = str_starts_with($sortParam, '-') ? 'desc' : 'asc';

        if (array_key_exists($column, $this->sorters)) {
            $this->sorters[$column]($query, $direction);
        } else {
            $query->orderBy($column, $direction);
        }
    }
}
