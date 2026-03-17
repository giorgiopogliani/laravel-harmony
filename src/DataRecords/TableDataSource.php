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
    public readonly Closure $record;

    public function __construct(
        private readonly Builder $query,
        private readonly array $sorters,
        private readonly int $perPage,
        ?Closure $record = null,
        private readonly array $metadata = [],
    ) {
        $this->record = $record ?? static fn (mixed $model) => $model;
    }

    public function additional(): array
    {
        return [
            'query' => ['per_page' => $this->perPage],
            ...$this->metadata,
        ];
    }

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
            ->through(function (mixed $model) use ($table) {
                $record = ($this->record)($model);

                $row = [
                    'id' => $model->getKey(),
                ];

                foreach ($table->columns() as $column) {
                    $row[$column->key()] = $column->value($model);
                }

                if ($record !== $model) {
                    $row = array_merge(
                        method_exists($record, 'toArray') ? $record->toArray() : (array) $record,
                        $row,
                    );
                }

                return $row;
            });

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
