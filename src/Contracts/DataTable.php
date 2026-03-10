<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

/** @template T */
interface DataTable
{
    /** @return array<Column<T>> */
    public function attributes(): array;

    /** @return array<Column<T>> */
    public function columns(): array;

    public function additional(): array;

    public function render(): mixed;
}
