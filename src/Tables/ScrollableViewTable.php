<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Inertia\Inertia;
use Inertia\ScrollProp;
use Override;
use Performing\Harmony\Contracts\DataSource;
use Performing\Harmony\Contracts\DataTable;

/**
 * @template T
 *
 * @implements DataTable<T>
 */
final class ScrollableViewTable implements DataTable
{
    /** @param DataTable<T> $table */
    public function __construct(
        private readonly DataTable $table,
    ) {}

    public DataSource $source {
        get => $this->table->source;
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
        return $this->table->filters();
    }

    #[Override]
    public function additional(): array
    {
        return [
            ...$this->table->additional(),
            'scrollable' => true,
        ];
    }

    #[Override]
    public function render(): ScrollProp
    {
        return Inertia::scroll($this->table->render()->additional($this->additional()));
    }
}
