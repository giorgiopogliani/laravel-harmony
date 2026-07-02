<?php

declare(strict_types=1);

namespace Performing\Harmony\Views;

use Override;
use Performing\Harmony\Contracts\View;
use Spatie\LaravelData\Data;

final class KanbanView extends Data implements View
{
    /**
     * @param list<string> $columns
     * @param array<string, string> $filters
     * @param list<string> $groups
     */
    public function __construct(
        public array $columns = [],
        public array $filters = [],
        public array $groups = [],
    ) {}

    #[Override]
    public function type(): string
    {
        return 'kanban';
    }

    #[Override]
    public function columns(): array
    {
        return $this->columns;
    }

    #[Override]
    public function grouped(): array
    {
        return $this->groups;
    }

    #[Override]
    public function filters(): array
    {
        return $this->filters;
    }
}
