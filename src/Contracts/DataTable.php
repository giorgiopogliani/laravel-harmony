<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;

/** @template T */
interface DataTable
{
    public function query(): Builder;

    /** @return array<Column<T>> */
    public function attributes(): array;

    /** @return array<Column<T>> */
    public function columns(): array;

    public function additional(): array;

    public function render(): mixed;
}
