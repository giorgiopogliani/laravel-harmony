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
use Performing\Harmony\Contracts\Record;

/**
 * @template T
 * @template B of Record<T>
 * @implements DataSource<T, B>
 */
final class QueryDataSource implements DataSource
{
    /**
     * @param  Builder<T>  $query
     * @param  Closure(mixed):B  $record
     */
    public function __construct(
        private readonly Builder $query,
        public readonly Closure $record,
    ) {}

    public function present(DataTable $table): ResourceCollection
    {
        $query = array_reduce(
            $table->filters(),
            static fn (Builder $query, Filter $filter) => $filter->apply($query),
            $this->query,
        );

        $data = $query
                ->paginate()
                ->withQueryString()
                ->through(function (mixed $model) use ($table) {
                    $record = ($this->record)($model);
                    $row = ['id' => $record->model()->getKey()];

                    foreach ($table->columns() as $column) {
                        $row[$column->key()] = $column->value($record);
                    }

                    return $row;
                });

        return JsonResource::collection($data)
            ->additional($table->additional());
    }
}
