<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Illuminate\Database\Eloquent\Builder;

abstract class Element
{
    abstract public function query(): Builder;

    abstract public function handle(): string;

    /** @return ActionComponent[] */
    public function bulkActions(): array
    {
        return [];
    }

    /** @return TableFilter */
    public function filters(): array
    {
        return [];
    }

    /** @return TableColumn[] */
    abstract public function columns(): array;

    /** @return FormField[] */
    public function fields(): array
    {
        return [];
    }
}
