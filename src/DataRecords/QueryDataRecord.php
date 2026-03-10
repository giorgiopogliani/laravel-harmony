<?php

declare(strict_types=1);

namespace Performing\Harmony\DataRecords;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Performing\Harmony\Contracts\DataSource;
use Performing\Harmony\Contracts\DataTable;
use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Contracts\Record;

/**
 * @template T
 * @implements DataSource<T>
 */
final class QueryDataRecord implements DataSource
{
    /**
     * @param  Builder  $query
     * @param  Closure(mixed): Record<T>  $record
     * @param  Collection<int, Filter>  $filters
     */
    public function __construct(
        private readonly Builder $query,
        private readonly Closure $record,
        private readonly Collection $filters = new Collection(),
    ) {}

    public function add(Filter $filter): self
    {
        $this->filters->push($filter);

        return $this;
    }

    public function present(DataTable $table): ResourceCollection
    {
        $query = $this->filters->reduce(
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
            ->additional([
                ...$table->additional(),
                'filters' => $this->filters->all(),
            ]);
    }
}
