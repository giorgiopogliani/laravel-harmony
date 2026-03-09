<?php

declare(strict_types=1);

namespace Performing\Harmony\Views;

use Override;
use Performing\Harmony\Contracts\View;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class TableView extends Data implements View
{
    /**
     * @param  list<string>  $columns
     * @param  array<string, string>  $filters
     */
    public function __construct(
        public array $columns,
        public array $filters = []
    ) {}

    #[Override]
    public function type(): string
    {
        return 'table';
    }

    #[Override]
    public function columns(): array
    {
        return $this->columns;
    }

    #[Override]
    public function grouped(): ?string
    {
        return null;
    }

    #[Override]
    public function filters(): array
    {
        return $this->filters;
    }
}
