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
 * @template T
 * @template B of object
 * @implements DataSource<T, B>
 */
final class QueryDataSource implements DataSource
{
    public readonly Closure $record;

    /**
     * @param  Builder<T>  $query
     * @param  (Closure(mixed):B)|null  $record
     */
    public function __construct(
        private readonly Builder $query,
        ?Closure $record = null,
        private readonly int $perPage = 15,
    ) {
        $this->record = $record ?? static fn (mixed $model) => $model;
    }

    public function additional(): array
    {
        return [
            'query' => ['per_page' => $this->perPage],
        ];
    }

    public function present(DataTable $table): ResourceCollection
    {
        $query = array_reduce(
            $table->filters(),
            static fn (Builder $query, Filter $filter) => $filter->apply($query),
            $this->query,
        );

        $data = $query
                ->paginate($this->perPage)
                ->withQueryString()
                ->through(function (mixed $model) use ($table) {
                    $record = ($this->record)($model);
                    $row = ['id' => $model->getKey()];

                    foreach ($table->columns() as $column) {
                        $row[$column->key()] = $column->value($record);
                    }

                    return $row;
                });

        return JsonResource::collection($data)
            ->additional($table->additional());
    }
}
