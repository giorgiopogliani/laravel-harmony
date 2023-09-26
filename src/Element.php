<?php

namespace Performing\Harmony;

abstract class Element
{
    abstract public function query(): \Illuminate\Database\Eloquent\Builder;

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
    abstract public function fields(): array;
}
