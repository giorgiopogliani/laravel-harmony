<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

/** @template T */
interface DataTable
{
    public DataSource $source { get; }

    /** @return array<Column<T>> */
    public function attributes(): array;

    /** @return array<Column<T>> */
    public function columns(): array;

    /** @return array<Filter> */
    public function filters(): array;

    /** @return array<string, mixed> */
    public function additional(): array;

    public function render(): mixed;
}
