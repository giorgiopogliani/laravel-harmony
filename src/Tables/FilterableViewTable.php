<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Illuminate\Support\Collection;
use Override;
use Performing\Harmony\Contracts\DataSource;
use Performing\Harmony\Contracts\DataTable;
use Performing\Harmony\Contracts\Filter;

/**
 * @template T
 *
 * @implements DataTable<T>
 */
final class FilterableViewTable implements DataTable
{
    /** @param DataTable<T> $table */
    public function __construct(
        private readonly DataTable $table,
        private Collection $filters = new Collection(),
    ) {}

    public DataSource $source {
        get => $this->table->source;
    }

    public function add(Filter $filter): void
    {
        $this->filters->push($filter);
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
    public function filters(): array
    {
        return $this->filters->all();
    }

    #[Override]
    public function additional(): array
    {
        return [
            ...$this->table->additional(),
            'filters' => $this->filters->all(),
        ];
    }

    #[Override]
    public function render(): mixed
    {
        return $this->table->source->present($this);
    }
}
