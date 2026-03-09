<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Performing\Harmony\Contracts\DataTable;
use Performing\Harmony\Contracts\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Override;

/**
 * @template T
 *
 * @implements DataTable<T>
 */
final class FilterableViewTable implements DataTable
{
    use HasPaginatedQuery;

    /**
     * @param  DataTable<T>  $table
     * @param  Collection<int, Filter>  $filters
     */
    public function __construct(
        private readonly DataTable $table,
        private Collection $filters = new Collection(),
    ) {}

    public function add(Filter $filter): void
    {
        $this->filters = $this->filters->add($filter);
    }

    #[Override]
    public function query(): Builder
    {
        return $this->filters->reduce(
            static fn (Builder $query, Filter $filter) => $filter->apply($query),
            $this->table->query(),
        );
    }

    #[Override]
    public function attributes(): array
    {
        return $this->table->attributes();
    }

    #[Override]
    public function columns(): array
    {
        return $this->table->columns();
    }

    #[Override]
    public function additional(): array
    {
        return [
            ...$this->table->additional(),
            'filters' => $this->filters->values()->all(),
        ];
    }

    #[Override]
    public function render(): mixed
    {
        return JsonResource::collection($this->rows())->additional($this->additional());
    }
}
