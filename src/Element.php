<?php

namespace Performing\Harmony;

abstract class Element
{
    abstract public function query(): \Illuminate\Database\Eloquent\Builder;

    abstract public function handle(): string;

    /** @return ActionComponent[] */
    abstract public function bulkActions(): array;

    /** @return TableFilter */
    abstract public function filters(): array;

    /** @return TableColumn[] */
    abstract public function columns(): array;

    /** @return FormField[] */
    abstract public function fields(): array;
}
