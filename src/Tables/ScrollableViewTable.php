<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Performing\Harmony\Contracts\DataTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\ScrollProp;
use Override;

/**
 * @template T
 * @implements DataTable<T>
 */
final class ScrollableViewTable implements DataTable
{
    use HasPaginatedQuery;

    /** @param DataTable<T> $table */
    public function __construct(
        private DataTable $table,
    ) {}

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
    public function query(): Builder
    {
        return $this->table->query();
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
        return Inertia::scroll(
            JsonResource::collection($this->rows())->additional($this->additional()),
        );
    }
}
