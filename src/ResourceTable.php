<?php

namespace Performing\Harmony;

use Performing\Harmony\Components\ActionComponent;
use Performing\Harmony\Components\Table\TableFilter;

interface ResourceTable
{
    public function query(): \Illuminate\Database\Eloquent\Builder;

    /** @return ActionComponent[] */
    public function bulkActions(): array;

    /** @return TableFilter */
    public function filters(): array;

    /** @return TableColumn[] */
    public function columns(): array;
}
